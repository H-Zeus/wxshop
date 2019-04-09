<?php

namespace App\Http\Controllers\wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use CURLFile;
use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    /**
     * @content 上传媒体文件
     */
    public function index(Request $request)
    {
        if($request->post()){
            //接收文件
            $file = $request->file;
            if($request->hasFile('file')){
                $str = $file->getClientMimeType(); //获取文件类型
                $ext = $file->getClientOriginalExtension(); //获取文件的后缀名
                $newFileName = date('YmdHis').mt_rand(1111,9999).'.'.$ext; //新的文件名称
                $path = $file->storeAs('',$newFileName,'uploads'); //上传,并返回文件名
                if($path){
                    $token = Wechat::GetAccessToken(); //获取access_token
                    $type = Wechat::getType($str);
                    $imgpath = public_path().'/uploads/material/'.date('Ymd').'/'.$path; //拼接文件名
                    $data = ['media' =>new CURLFile(realpath($imgpath),$str,$path)];
                    $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=$type";
                    $dataObj = Wechat::HttpPost($url,$data); //对象格式 数据
                    $array = json_decode($dataObj,true); //数组格式 数据
                    if(isset($array['errcode'])){
                        die($array['errcode']);
                    }else{
                        $media_id = $array['media_id'];
                        $data = $request->all();
                        unset($data['_token']);
                        unset($data['file']);
                        $data['media_id'] = $media_id;
                        $data['m_path'] = '/uploads/material/'.date('Ymd').'/'.$path;
                        $data['create_time'] = time();
                        $res = DB::table('material')->insert($data);
                        if($res){

                            return '提交成功';
                        }else{
                            
                            return '提交失败';
                        }
                    }
                }else{

                    return '上传失败';
                }
            }else{

                return '没有文件被上传';
            }
        }else{
            
            return view('wechat.index');
        }
    }
}