<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\DB;
use App\Common;
use App\Tools\email\PHPMailer;
use App\Tools\email\SMTP;
use App\Tools\sms\lib\Ucpaas;

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
            Redis::setex('access_token',7000,$token);
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
            'audio' => 'music',
            'application' => 'voice',
            'video' => 'video'
        ];

        return $allow_type[$arr];
    }

    /**
     * @content 回复消息
     * @param string $type 信息类型
     * @param $fromUserName 接收方帐号
     * @param $toUserName 发送方帐号
     */
    public static function ReplyMessage($type,$fromUserName,$toUserName)
    {
        $time = time();
        $array = DB::table('material')->where('type',$type)->orderBy('id','desc')->first();
        $Title = $array->m_title;
        $Description = $array->m_content;
        $PicUrl = 'http://hantian.shop'.$array->m_path;
        $Url = $array->m_url;
        $media_id = $array->media_id;
        if($type == 'text'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
            $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$Description);
        }else if($type == 'image'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                    </xml>";
                $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$media_id);
        }else if($type == 'voice'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Voice>
                    </xml>";
                $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$media_id);
        }else if($type == 'video'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Video>
                            <MediaId><![CDATA[%s]]></MediaId>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                        </Video>
                    </xml>";
            $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$media_id,$Title,$Description);
        }else if($type == 'music'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Music>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <MusicUrl><![CDATA[%s]]></MusicUrl>
                            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                            <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        </Music>
                    </xml>";
            $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$Title,$Description,$PicUrl,$PicUrl,$media_id);
        }else if($type == 'news'){
            $Htpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime><![CDATA[%s]]></CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[%s]]></Title>
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                        </Articles>
                    </xml>";
            $resultStr = sprintf($Htpl,$fromUserName,$toUserName,$time,$type,$Title,$Description,$PicUrl,$Url);
        }
        echo $resultStr;


        
        // $token = Wechat::GetAccessToken(); //获取access_token
        // $PicUrl = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$media_id";
    }

    /**
     * @content 获取关注列表
     */
    public static function GetOpenIdList()
    {
        $token = self::GetAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token";
        $info = json_decode(file_get_contents($url),true);

        return $info['data']['openid'];
    }

    /**
     * @content 获取标签列表
     */
    public static function GetTagList()
    {
        if(Redis::exists('tagListInfo')){
            $info = json_decode(Redis::get('tagListInfo'),true);
        }else{
            $tagListUrl = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".Wechat::GetAccessToken();
            $info = json_decode(file_get_contents($tagListUrl),true)['tags'];
            Redis::set('tagListInfo',json_encode($info,JSON_UNESCAPED_UNICODE));
        }
        
        return $info;
    }

    /**
     * @content 微信登录授权
     */
    public static function Authorization($code)
    {
        $appid = env('WX_APPID');
        $secret = env('WX_APPSECRET');
        //通过code换取网页授权access_token
        $tokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $data = json_decode(file_get_contents($tokenUrl),true);
        $access_token = $data['access_token'];
        $openid = $data['openid'];
        //检验授权凭证（access_token）是否有效
        $checkTokenUrl = "https://api.weixin.qq.com/sns/auth?access_token=$access_token&openid=$openid";
        $res = json_decode(file_get_contents($checkTokenUrl),true);
        return $res;
    }
}