<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_decode;

class GroupController extends Controller
{
    /**
     * @content 群发消息
     */
    public function groupSend(Request $request)
    {
        if($request->ajax()){
            if(Redis::exists('fileInfo')){
                //获取文件
                $fileInfo = json_decode(Redis::get('fileInfo'),true);
                $fileType = $fileInfo['type'];
                $fileMediaId = $fileInfo['media_id']; //新获取的media_id
                Redis::del('fileInfo');
            }
            $token = Wechat::GetAccessToken();
            
            $openIdList = Wechat::GetOpenIdList();
            $type = $request->type;
            $tag = $request->tag;
            //全体群发
            if($tag == 0){
                $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$token";
                if($type == 'mpnews video'){ //图文 视频
                    $title = $request->title;
                    $content = $request->content;
                    //图文
                    if($fileType == 'thumb'){
                        $postdata = [
                            "touser"=>$openIdList,
                            "msgtype"=>"mpnews",
                            "mpnews"=>[
                                "media_id"=>$fileMediaId,
                                'title'=>$title,
                                'description'=>$content
                            ],
                            "send_ignore_reprint"=>1
                        ];
                    }else{
                        $postdata = [
                            "touser"=>$openIdList,
                            "msgtype"=>"mpvideo",
                            "mpvideo"=>[
                                "media_id"=>$fileMediaId,
                                'title'=>$title,
                                'description'=>$content
                            ],
                        ];
                    }
                }else if($type == 'image voice'){ //图片 语音
                    if($fileType == 'image'){
                        $postdata = [
                            "touser"=>$openIdList,
                            "msgtype"=>'image',
                            "image"=>[
                                "media_id"=>$fileMediaId,
                            ]
                        ];
                    }else{
                        $postdata = [
                            "touser"=>$openIdList,
                            "msgtype"=>'voice',
                            "voice"=>[
                                "media_id"=>$fileMediaId,
                            ]
                        ];
                    }
                }else if($type == 'text'){ //文本
                    $content = $request->content;
                    $postdata = [
                        "touser"=>$openIdList,
                        "msgtype"=>"text",
                        "text"=>[
                            "content"=>$content
                        ],
                    ];
                }
            }else{ //根据标签群发
                $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$token";
                if($type == 'mpnews video'){ //图文 视频
                    $title = $request->title;
                    $content = $request->content;
                    //图文
                    if($fileType == 'thumb'){
                        $postdata = [
                            "filter"=>[
                                'is_to_all' => false,
                                'tag_id' => $tag
                            ],
                            "mpnews"=>[
                                "media_id"=>$fileMediaId,
                                'title'=>$title,
                                'description'=>$content
                            ],
                            "msgtype"=>"mpnews",
                            "send_ignore_reprint"=>1
                        ];
                    }else{
                        $postdata = [
                            "filter"=>[
                                'is_to_all' => false,
                                'tag_id' => $tag
                            ],
                            "mpvideo"=>[
                                "media_id"=>$fileMediaId,
                                'title'=>$title,
                                'description'=>$content
                            ],
                            "msgtype"=>"mpvideo",
                        ];
                    }
                }else if($type == 'image voice'){ //图片 语音
                    if($fileType == 'image'){
                        $postdata = [
                            "filter"=>[
                                'is_to_all' => false,
                                'tag_id' => $tag
                            ],
                            "msgtype"=>'image',
                            "image"=>[
                                "media_id"=>$fileMediaId,
                            ]
                        ];
                    }else{
                        $postdata = [
                            "filter"=>[
                                'is_to_all' => false,
                                'tag_id' => $tag
                            ],
                            "msgtype"=>'voice',
                            "voice"=>[
                                "media_id"=>$fileMediaId,
                            ]
                        ];
                    }
                }else if($type == 'text'){ //文本
                    $content = $request->content;
                    $postdata = [
                        "filter"=>[
                                'is_to_all' => false,
                                'tag_id' => $tag
                        ],
                        "msgtype"=>"text",
                        "text"=>[
                            "content"=>$content
                        ],
                    ];
                }
            }
            
            $postjson = json_encode($postdata,JSON_UNESCAPED_UNICODE);
            $res = json_decode(Wechat::HttpPost($url,$postjson),true);
            if($res['errcode'] == 0){
                return '发送成功';
            }else{
                return '发送失败！错误原因：'.$res['errmsg'];
            }
        }else{
            $tagInfo = Wechat::GetTagList();

            return view('admin.groupmessage',['tagInfo'=>$tagInfo]);
        }
    }

    /**
     * @content 文件上传
     */
    public function uploadFile(Request $request)
    {
        $file = $request->file;
        $str = $file->getClientMimeType(); //获取文件类型
        $fileName = $file->getClientOriginalName(); //获取原始文件名
        $name = explode('.',$fileName)[0];
        $arr = explode('/',$str)[0];
        $allow_type = [
            'image' => 'thumb',
            'application' => 'voice',
            'video' => 'video'
        ];
        $type = $allow_type[$arr];
        $newFileName = date('YmdHis').mt_rand(1111,9999).$fileName; //新的文件名称
        $path = $file->storeAs('',$newFileName,'uploads'); //上传,并返回文件名
        $token = Wechat::GetAccessToken(); //获取access_token
        $imgpath = public_path().'/uploads/material/'.date('Ymd').'/'.$path; //拼接文件名
        $data = ['media' =>new \CURLFile(realpath($imgpath),$str,$path)];
        if($type == 'video'){
            $data['description'] = json_encode([
                'title'=>$name,
                'introduction' => '视频'
            ]);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$type"; //永久素材
        $dataObj = Wechat::HttpPost($url,$data); //对象格式 数据
        $array = json_decode($dataObj,true); //数组格式 数据
        $media_id = $array['media_id'];
        if($type == 'video'){
            $video_url = " https://api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token=$token";
            $data = [
                'media_id' => $media_id,
                'title'=>$name,
                'description' => '视频'
            ];
            $dataObj = Wechat::HttpPost($video_url,$data); //对象格式 数据
            $media_id = json_decode($dataObj,true)['media_id']; //数组格式 数据
        }
        $info = json_encode([
            'type' => $type,
            'media_id' => $media_id
        ]);
        Redis::set('fileInfo',$info);
    }
}
