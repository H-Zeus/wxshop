<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_encode;

class WechatAdminController extends Controller
{
    /**
     * @content 首页
     */
    public function index()
    {
        return view('admin.index');
    }

    /**********************************************
     *                 回复用户消息                 *
     **********************************************/
    /** 回复文字消息 */
    public function textmessage()
    {

        return view('admin.replymessage.textmessage');
    }
    /** 回复 图片、语音、视频、音乐 消息 */
    public function mixedmessage(Request $request)
    {
        $messageType = explode('?',$request->getRequestUri())[1];
        
        return view('admin.replymessage.mixedmessage',['messageType'=>$messageType]);
    }
    /** 回复图文消息 */
    public function newsmessage()
    {

        return view('admin.replymessage.newsmessage');
    }

     /**
     * @content 上传媒体文件
     */
    public function upmessage(Request $request)
    {
        $data = $request->all();
        $messageType = $request->messageType;
        //处理回复消息
        if($messageType == 'text'){
            $m_content = $request->m_content; //获取文本消息内容
            $data = [
                'type' => $messageType,
                'm_content' => $m_content
            ];
        }else{
            if($request->hasFile('file')){
                $file = $request->file;
                $str = $file->getClientMimeType(); //获取文件类型
                // "application/octet-stream" amr
                // audio/mp3 
                // video/mp4
                $ext = $file->getClientOriginalExtension(); //获取文件的后缀名
                $newFileName = date('YmdHis').mt_rand(1111,9999).'.'.$ext; //新的文件名称
                $path = $file->storeAs('',$newFileName,'uploads'); //上传,并返回文件名
                $token = Wechat::GetAccessToken(); //获取access_token
                // dd($token);
                $type = Wechat::getType($str);
                $imgpath = public_path().'/uploads/material/'.date('Ymd').'/'.$path; //拼接文件名
                $data = ['media' =>new \CURLFile(realpath($imgpath),$str,$path)];
                if($type == 'video'){
                    $data['description'] = json_encode([
                        'title'=>$request->m_title,
                        'introduction' => $request->m_content
                    ]);
                }
                // $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=$type"; //临时素材
                $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$type"; //永久素材
                $dataObj = Wechat::HttpPost($url,$data); //对象格式 数据
                $array = json_decode($dataObj,true); //数组格式 数据
                // dd($array);
                $media_id = $array['media_id'];
                $data = $request->all();
                unset($data['_token']);
                unset($data['file']);
                unset($data['messageType']);
                $data['media_id'] = $media_id;
                $data['m_path'] = '/uploads/material/'.date('Ymd').'/'.$path;
                $data['create_time'] = time();
                $data['type'] = $messageType;
            }else{

                return '没有文件被上传';
            }
        }
        $res = DB::table('material')->insert($data);
        if($res){

            return '提交成功';
        }else{
            
            return '提交失败';
        }
    }

    /**
     * @content 设置消息类型
     */
    public function settype()
    {
        if($_POST){
            $type = $_POST['type'];
            $path = config_path('messagetype.php');
            $config = [];
            $config['subscribe'] = $type;
            $str = '<?php return '.var_export($config,true).'; ?>';
            $res = file_put_contents($path,$str);
            if($res){
                return '设置成功';
            }else{
                return '设置失败';
            }
        }else{
            $type = config('messagetype.subscribe');
            return view('admin.replymessage.settype',['type'=>$type]);
        }
    }
}
