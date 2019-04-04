<?php

namespace App\Http\Controllers\wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    /**
     * @content 微信绑定服务器校验
     */
    public function check(Request $request)
    {
        $signature = $request->signature; //微信加密签名
        $timestamp = $request->timestamp; //时间戳
        $nonce = $request->nonce; //随机数
        if($this->CheckSignature($signature,$timestamp,$nonce)){
            echo $echostr;exit;
        }
    }

    /**
     * @content 校验微信签名
     */
    private function CheckSignature($signature,$timestamp,$nonce)
    {
        
        $token = env('WEIXINTOKEN');
        $arr = [$token,$timestamp,$nonce];
        sort($arr,SORT_STRING); //进行字典序排序
        $str = implode($arr); //拼接成字符串
        $sign = sha1($str); //进行sha1加密
        //对比sign
        if($sign == $signature){
            return true;
        }else{
            return false;
        }
    }
}
