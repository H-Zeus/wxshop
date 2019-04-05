<?php

namespace App\Http\Controllers\wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;

class WechatController extends Controller
{
    /**
     * @content 微信绑定服务器校验
     */
    public function check()
    {
        //推送消息
        $this->responseMsg();

        //校验微信签名
        $echostr = $_GET['echostr']; //随机字符串
        if($this->CheckSignature()){
            echo $echostr;exit;
        }
    }

    /**
     * @content 推送消息
     */
    public function responseMsg()
    {
        //获取微信请求的所有内容
        $postStr = file_get_contents("php://input");
        $postObj = simplexml_load_string($postStr);//转换成对象
        $fromUserName = $postObj->FromUserName;
        $toUserName = $postObj->ToUserName;
        $keywords = trim($postObj->Content);
        $time = time();
        $MsgType = "text";
        $tpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                </xml>";
        //首次关注回复消息
        if($postObj->MsgType == 'event'){
            //判断是一个关注事件
            if($postObj->Event == 'subscribe'){
                $content = "欢迎来到我的小屋~~（小屋里藏着个图灵哦）";
                $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
                echo $resultStr;exit;
            }
        }
        //关键词回复消息
        if($keywords == '你好'){
            $content = "欢迎来到我的小屋~~（小屋里藏着个图灵哦）";
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }else if(strpos($keywords,'天气')!=0){ //查询天气
            //NowApi接口 参数
            $appkey = '41389';
            $sign = '3dd3bca194977e7a41d65e4904a8bf1b';
            $weaid = substr($keywords,0,strpos($keywords,'天气')); //获取查询城市名称
            $url = "http://api.k780.com/?app=weather.today&weaid=$weaid&appkey=$appkey&sign=$sign&format=json";
            $url = file_get_contents($url);
            $data = json_decode($url,true)['result'];
            $msg = '城市：'.$data['citynm']."\r\n"
            .'天气：'.$data['weather']."\r\n"
            .'今日气温：'.$data['temperature']."\r\n"
            .'当前温度：'.$data['temperature_curr']."\r\n"
            .'湿度：'.$data['humidity']."\r\n"
            .'风向：'.$data['wind']."\r\n"
            .'风力：'.$data['winp']."\r\n";
            $content = $msg;
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }else if($keywords=='时间'){ //查询当前时间
            //NowApi接口 参数
            $appkey = '41389';
            $sign = '3dd3bca194977e7a41d65e4904a8bf1b';
            $url = "http://api.k780.com/?app=life.time&appkey=$appkey&sign=$sign&format=json";
            $url = file_get_contents($url);
            $data = json_decode($url,true)['result'];
            $msg = $data['datetime_2']."\r\n".$data['week_2'];
            $content = $msg;
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }else{
            //调用图灵机器人回复 关键词
            $data = [
                'perception' => [
                    'inputText' => [
                        'text' => $keywords
                    ]
                ],
                'userInfo' => [
                    'apiKey' => '2a9d1f82c9394aa4b7e15b6038aea2e2',
                    'userId' => '9517'
                ]
            ];
            $post_data = json_encode($data);
            $tuling_url = "http://openapi.tuling123.com/openapi/api/v2";
            $re = Wechat::HttpPost($tuling_url,$post_data);
            $msg = json_decode($re,true)['results'][0]['values']['text'];
            $content = $msg;
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }
    }

    /**
     * @content 校验微信签名
     */
    private function CheckSignature()
    {
        $signature = $_GET['signature']; //微信加密签名
        $timestamp = $_GET['timestamp']; //时间戳
        $nonce = $_GET['nonce']; //随机数
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

    /**
     * @content 测试
     */
    public function test()
    {
        $arr = array(
            array("username"=>'张鹏飞','age'=>23,'sex'=>'男'),
            array("username"=>'丽丽','age'=>20,'sex'=>'女'),
            array("username"=>'小明','age'=>16,'sex'=>'男'),
            array("username"=>'大明','age'=>20,'sex'=>'男'),
        );
        static $info = [];
        foreach($arr as $k=>$v){
            if($v['sex']=='男'){
                unset($v['sex']);
                $info['男'][$k] = $v;
            }else{
                unset($v['sex']);
                $info['女'][$k] = $v;
            }
        }
        print_r($info);
        // dd($arr);
    }  
}
