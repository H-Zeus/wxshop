<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_decode;


class Wechat extends Model
{
    /**
     * @content 封装一个post请求
     */
    public static function HttpPost($url,$post_data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL,$url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER,0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //跳过HTTPS验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据

        return $data;
    }

    /**
     * @content 生成access_token
     */
    public static function GetAccessToken()
    {
        $grant_type	= env('WX_GRANT_TYPE');
        $appid	= env('WX_APPID');
        $secret	= env('WX_APPSECRET');
        if(Redis::exists('access_token')){
            $token = Redis::get('access_token');
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=$grant_type&appid=$appid&secret=$secret";
            $token = json_decode(file_get_contents($url),true)['access_token'];
            Redis::setex('access_token',7140,$token);
        }
        return $token;
    }

    /**
     * @content 获取文件类型
     */
    public static function getType($str)
    {
        $arr = explode('/',$str)[0];
        $allow_type = [
            'image' => 'image',
            'audio' => 'voice',
            'application' => 'video'
        ];

        return $allow_type[$arr];
    }
}