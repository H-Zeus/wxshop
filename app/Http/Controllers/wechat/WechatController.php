<?php

namespace App\Http\Controllers\wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Model\Order;

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
        if($this->CheckSignature()){
            $echostr = $_GET['echostr']; //随机字符串
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
                $token = Wechat::GetAccessToken();
                //获取关注 用户信息
                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$fromUserName&lang=zh_CN";
                $info = json_decode(file_get_contents($url),true);
                $info['tagid_list'] = implode('、',$info['tagid_list']);
                $userName = $info['nickname'];
                $info['nickname'] = json_encode($info['nickname']);
                //将用户信息存入数据库
                DB::table('wx_userinfo')->insert($info);
                Redis::del('userListInfo');
                //拉取授权信息
                $appid = env('WX_APPID');
                $redirectUri = urlencode("http://www.hantian.shop/admin/wxtplogin");
                $wxTPLoginUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirectUri&response_type=code&scope=snsapi_userinfo&state=9517#wechat_redirect"; 
                //判断用户是否已绑定
                $res = DB::table('shop_user')->where('openid',$info['openid'])->first();
                if($res){
                    $content = "$userName 欢迎您回来";
                }else{
                    $content = '尊敬的用户您好，雪天网感谢您的使用，首次关注需要您绑定本网站的账户，以便更方便的为您提供服务'."<a href='$wxTPLoginUrl'>".'点击绑定</a>';
                }
                //回复自定义消息
                // $type = config('messagetype.subscribe');
                // $resultStr = Wechat::ReplyMessage($type,$fromUserName,$toUserName);
                $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
                echo $resultStr;exit;
            }else if($postObj->Event == 'CLICK'){  //点击菜单
                $EventKey = $postObj->EventKey;//菜单的自定义的key值，可以根据此值判断用户点击了什么内容，从而推送不同信息  
                switch($EventKey){
                    case "新品推荐" :
                        $array = DB::table('shop_goods')->orderBy('create_time','desc')->limit(5)->get();
                        static $info;
                        foreach($array as $v){
                            $info[] = [
                                'Title' => $v->goods_name,
                                'Description' => '价格：'.$v->self_price.' 库存：'.$v->goods_num,
                                'PicUrl' => 'http://hantian.shop/uploads/goodsimg/'.$v->goods_img,
                                'Url' => "http://hantian.shop/shopcontent/".$v->goods_id
                            ];
                        }
                        $Htpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>" . count($array) . "</ArticleCount>
                                    <Articles>";
                        foreach($info as $v){
                            $Htpl .= "<item>
                                          <Title><![CDATA[" . $v['Title'] . "]]></Title>
                                          <Description><![CDATA[" . $v['Description'] . "]]></Description>
                                          <PicUrl><![CDATA[" . $v['PicUrl'] . "]]></PicUrl>
                                          <Url><![CDATA[" . $v['Url'] . "]]></Url>
                                      </item>";
                        }
                        $Htpl .= "</Articles></xml>";
                        $type = 'news';
                        $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type);
                        echo $resultStr;exit;
                    break;
                }
            }
        }
        //关键词回复消息
        if($keywords == '你好'){
            //测试
            // $resultStr = Wechat::ReplyMessage('music',$fromUserName,$toUserName);
            // echo $resultStr;exit;
            $content = "ds欢迎来到我的小屋~~（小屋里藏着个图灵哦）";
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }else if($keywords == '登录'){
            $appid = env('WX_APPID');
            $redirectUri = urlencode("http://www.hantian.shop/admin/wxtplogin");
            $wxTPLoginUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirectUri&response_type=code&scope=snsapi_userinfo&state=9517#wechat_redirect"; 
            $content = $wxTPLoginUrl;
            $resultStr = sprintf($tpl,$fromUserName,$toUserName,$time,$MsgType,$content);
            echo $resultStr;exit;
        }else if(strpos($keywords,'商品：') === 0){ //查询商品  返回图文信息
            /**************
             * 单图文消息
             *************/
            // $Htpl = "<xml>
            //             <ToUserName><![CDATA[%s]]></ToUserName>
            //             <FromUserName><![CDATA[%s]]></FromUserName>
            //             <CreateTime><![CDATA[%s]]></CreateTime>
            //             <MsgType><![CDATA[%s]]></MsgType>
            //             <ArticleCount>1</ArticleCount>
            //             <Articles>
            //                 <item>
            //                     <Title><![CDATA[%s]]></Title>
            //                     <Description><![CDATA[%s]]></Description>
            //                     <PicUrl><![CDATA[%s]]></PicUrl>
            //                     <Url><![CDATA[%s]]></Url>
            //                 </item>
            //             </Articles>
            //         </xml>";
            // $type = 'news';
            // $keywords = explode('：',$keywords)['1'];
            // $array = DB::table('shop_goods')->where('goods_name','like',"%$keywords%")->orderBy('create_time','desc')->first();
            // $Title = $array->goods_name;
            // $Description = '价格：'.$array->self_price.'库存：'.$array->goods_num;
            // $PicUrl = '/uploads/goodsimg/'.$array->goods_img;
            // $Url = "http://hantian.shop/shopcontent/".$array->goods_id;

            // $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$Title,$Description,$PicUrl,$Url);

            /*************
             * 多图文消息
             *************/
            $keywords = explode('：',$keywords)['1'];
            $array = DB::table('shop_goods')->where('goods_name','like',"%$keywords%")->get();
            static $info;
            foreach($array as $v){
                $info[] = [
                    'Title' => $v->goods_name,
                    'Description' => '价格：'.$v->self_price.' 库存：'.$v->goods_num,
                    'PicUrl' => 'http://hantian.shop/uploads/goodsimg/'.$v->goods_img,
                    'Url' => "http://hantian.shop/shopcontent/".$v->goods_id
                ];
            }
            $Htpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>" . count($array) . "</ArticleCount>
                            <Articles>";
            foreach($info as $v){
                $Htpl .= "<item>
                                <Title><![CDATA[" . $v['Title'] . "]]></Title>
                                <Description><![CDATA[" . $v['Description'] . "]]></Description>
                                <PicUrl><![CDATA[" . $v['PicUrl'] . "]]></PicUrl>
                                <Url><![CDATA[" . $v['Url'] . "]]></Url>
                              </item>";
            }
            $Htpl .= "</Articles></xml>";
            $type = 'news';
            $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type);

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
        }else if(strpos($keywords,'订单') === 0){
            Order::orderInfo($fromUserName,$keywords);
            exit;
        }else{
            //调用图灵机器人回复 关键词
            $data = [
                'perception' => [
                    'inputText' => [
                        'text' => $keywords
                    ]
                ],
                'userInfo' => [
                    'apiKey' => 'fed693c74286411ca2e75c0f5d6eac3c',
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
        Redis::flushall();
        $token = Wechat::GetAccessToken();
        echo $token;exit;
    }  
}
