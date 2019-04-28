<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\phpqrcode\QRcode;
use App\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Model\Wechat;
use function GuzzleHttp\json_encode;

class QrcodeController extends Controller
{
    /**
     * @content 生成二维码
     */
    public function getQRcode()
    {
        include app_path('Tools/qrcode/phpqrcode.php');
        $userid = md5(time()).Common::createcode(8);
        $value = 'http://www.hantian.shop/user/qrlogin/'.$userid;
        Redis::set('abc',$value);
        QRcode::png($value,'qrcode.png');
        
        return view('user.qrcode',['userid'=>$userid]);
    }

    /**
     * @content 微信扫码登录
     */
    public function qrLogin($userid)
    {
        $appid = env('WX_APPID');
        $redirect_uri = urlencode("http://www.hantian.shop/user/tplogin");
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=$userid#wechat_redirect";

        return redirect($url);
    }

    /**
     * 微信授权登录
     */
    public function tpLogin(Request $request)
    {
        $code = $request->code;
        $userid = $request->state;
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
        if($res['errcode'] == 0){
            //拉取用户信息
            $userInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            $userInfo = json_decode(file_get_contents($userInfoUrl),true);
            Redis::set('qrcodeName',$userInfo['nickname']);
            $data = [
                'openid' => $userInfo['openid'],
                'userid' => $userid,
                'status' => 2
            ];
            DB::table('qrcode')->insert($data);
            
            return view('user.surelogin',['userinfo'=>$userInfo]);
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'<br>错误信息：'.$res['errmsg'];
        }
    }

    /**
     * 获取状态
     */
    public function getStatus(Request $request)
    {
        $userid = $request->userid;
        $status = DB::table('qrcode')->where('userid',$userid)->value('status');
        if(!empty($status)){
            return $status;
        }else{
            return 1;
        }
    }
    /**
     * 修改状态
     */
    public function changestatus(Request $request)
    {
        $openid = $request->openid;

        $res = DB::table('qrcode')->where('openid',$openid)->update(['status'=>3]);
        if($res){
            $check = DB::table('shop_user')->where('openid',$openid)->first();
            //判断是否存在该用户
            if($check){
                $user_id = $check->user_id;
                $user_name = $check->user_name;
            }else{
                //入库
                $user_name = Redis::get('qrcodeName');
                $data = [
                    'openid' => $openid,
                    'user_pwd' => encrypt('123123'),
                    'user_name' => json_encode($user_name)
                ];
                $user_id = DB::table('shop_user')->insertGetId($data);
            }
            $userInfo = [
                'user_id' => $user_id,
                'user_name' => $user_name
            ];
            Redis::set('userInfo',json_encode($userInfo));
            return '登录成功';
        }else{
            return '登录失败';
        }
    }

    public function test()
    {
        echo Redis::get('userInfo');
        exit;
        echo Redis::get('abc');
    }
}
