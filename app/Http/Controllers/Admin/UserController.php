<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\DB;
use App\Common;
use App\Model\Order;

class UserController extends Controller
{
    /**
     * @content 用户列表
     */
    public function index()
    {
        // Redis::del('tagListInfo');
        // Redis::del('userListInfo');
        if(Redis::exists('userListInfo')){
            $info = json_decode(Redis::get('userListInfo'),true);
        }else{
            $token = Wechat::GetAccessToken();
            $userListUrl = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token";
            $openId = json_decode(file_get_contents($userListUrl),true)['data']['openid'];
            static $info = [];
            foreach($openId as $v){
                $userInfoUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$v";
                $info[] = json_decode(file_get_contents($userInfoUrl),true);
            }
            Redis::set('userListInfo',json_encode($info,JSON_UNESCAPED_UNICODE));
        }
        $tagInfo = Wechat::GetTagList();
        array_unshift($tagInfo,['id'=>0,'name'=>'无标签']);

        return view('admin.usercontrol',['info'=>$info,'tagInfo'=>$tagInfo]);
    }
    /**
     * @content 标签管理
     */
    public function tagIndex()
    {
        // Redis::del('tagListInfo');
        $info = Wechat::GetTagList();
        return view('admin.tag.taglist',['info'=>$info]);
    }
    /**
     * @content 添加标签
     */
    public function tagAdd(Request $request)
    {
        if($request->ajax()){
            $tagName = $request->tagName;
            $tagAddUrl = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".Wechat::GetAccessToken();
            $data = json_encode(['tag'=>['name'=> $tagName]],JSON_UNESCAPED_UNICODE);
            $res = json_decode(Wechat::HttpPost($tagAddUrl,$data),true);
            if(array_key_exists('errcode',$res)){
                return '创建失败<br>错误代码：'.$res['errcode'].'<br>错误信息：'.$res['errmsg'];
            }else{
                Redis::del('tagListInfo');
                return '创建成功';
            }
        }else{
            return view('admin.tag.tagadd');
        }
    }

    /**
     * @content 删除标签
     */
    public function tagDel(Request $request)
    {
        $tagid = $request->tagId;
        $tagDelUrl = "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=".Wechat::GetAccessToken();
        $data = json_encode(['tag'=>['id'=> $tagid]],JSON_UNESCAPED_UNICODE);
        $res = json_decode(Wechat::HttpPost($tagDelUrl,$data),true)['errcode'];
        if($res == 0){
            Redis::del('tagListInfo');
            Redis::del('userListInfo');
            return '删除成功';
        }else{
            if($res == -1){$error = '系统繁忙';}
            if($res == '45058'){$error = '不能修改0/1/2这三个系统默认保留的标签';}
            return '删除失败<br>'.$error;
        }
    }

    /**
     * @content 修改用户标签
     */
    public function userTagUpd(Request $request)
    {
        $token = Wechat::GetAccessToken();
        $id = $request->id;
        $openid = $request->openid;
        if($id == 0){
            $userInfoUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid";
            $id = json_decode(file_get_contents($userInfoUrl),true)['groupid'];
            $userTagUpdUrl = "https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=$token";
        }else{
            $userTagUpdUrl = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=$token";
        }
        
        $data = json_encode([
                'openid_list'=>[$openid],
                'tagid'=>$id
            ],JSON_UNESCAPED_UNICODE);
        $res = json_decode(Wechat::HttpPost($userTagUpdUrl,$data),true)['errcode'];
        if($res == 0){
            Redis::del('tagListInfo');
            Redis::del('userListInfo');
            return '修改成功';
        }else{
            if($res == -1){$error = '系统繁忙';}
            if($res == '45159'){$error = '非法的标签';}
            if($res == '49003'){$error = '传入的openid不属于此AppID';}
            return '修改失败<br>'.$error;
        }
    }


    /**
     * @content 微信第三方登录
     */
    public function wxTPLogin(Request $request)
    {
        $code = $request->code;
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
            // $userInfo = json_decode(file_get_contents($userInfoUrl),true);
            $openid = json_decode(file_get_contents($userInfoUrl),true)['openid'];
            Redis::set('getOpenid',$openid);
            return view('admin.bindlogin',['openid'=>$openid]);
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'<br>错误信息：'.$res['errmsg'];
        }
    }

    /**
     * @content 绑定用户
     */
    public function bindLogin(Request $request)
    {
        // $openid = 'o5WxR1QYcReK0b4O6UfSMlDRMGAs';
        // return view('admin.bindlogin',['openid'=>$openid]);
        $code = Redis::get('bindcode');
        $binduser = Redis::get('binduser');
        $user = $request->user;
        $openid = $request->openid;
        if($user !== $binduser){return '操作异常！';}
        if($code === $request->code){
            //绑定用户
            $res1 = DB::table('shop_user')->where('user_email',$user)->update(['openid'=>$openid]);
            $res2 = DB::table('shop_user')->where('user_tel',$user)->update(['openid'=>$openid]);
            if($res1 || $res2){
                return '绑定成功';
            }else{
                return '绑定失败';
            }
        }else{
            return '验证码错误';
        }
    }

    /**
     * @content 发送验证码
     */
    public function sendCode(Request $request)
    {
        $user = $request->user;
        Redis::set('binduser',$user);
        if(empty($user)){return '账号不能为空！';}
        //判断是邮箱还是手机号
        $pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i';
        preg_match($pattern, $user, $matches);
        $code = Common::createcode(4);
        //模拟发送成功
        // Redis::setex('bindcode',300,$code);
        // dd(Redis::get('bindcode'));

        if($matches){ // 邮箱 发送邮箱
            $exists = DB::table('shop_user')->where('user_email',$user)->first();
            if($exists == null){return '账号不存在';}
            $res = Order::sendEmail($user,$code);
        }else{ //手机号 发送短信
            $exists = DB::table('shop_user')->where('user_tel',$user)->first();
            if($exists == null){return '账号不存在';}
            $res = Order::sendsms($user,$code);
        }
        if($res){
            Redis::setex('bindcode',300,$code);
            return '发送成功,验证码5分钟内有效';
        }else{
            return '发送失败';
        }
    }

    /**
     * @content 菜单-我的订单
     */
    public function myOrder(Request $request){
        $code = $request->code;
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
            $openid = json_decode(file_get_contents($userInfoUrl),true)['openid'];
            $userInfo = DB::table('shop_user')->where('openid',$openid)->first();
            $userInfo = [
                'user_id' => $userInfo->user_id,
                'user_name' => $userInfo->user_email.$userInfo->user_tel
            ];
            session(['userInfo' => $userInfo]);
            return redirect('/recorddetail');
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'错误信息：'.$res['errmsg'];
        }
    }
    /**
     * @content 菜单-我的购物车
     */
    public function shopCar(Request $request){
        $code = $request->code;
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
            $openid = json_decode(file_get_contents($userInfoUrl),true)['openid'];
            $userInfo = DB::table('shop_user')->where('openid',$openid)->first();
            $userInfo = [
                'user_id' => $userInfo->user_id,
                'user_name' => $userInfo->user_email.$userInfo->user_tel
            ];
            session(['userInfo' => $userInfo]);
            return redirect('/shopcart');
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'错误信息：'.$res['errmsg'];
        }
    }
    /**
     * @content 菜单-收货地址
     */
    public function shopAddress(Request $request){
        $code = $request->code;
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
            $openid = json_decode(file_get_contents($userInfoUrl),true)['openid'];
            $userInfo = DB::table('shop_user')->where('openid',$openid)->first();
            $userInfo = [
                'user_id' => $userInfo->user_id,
                'user_name' => $userInfo->user_email.$userInfo->user_tel
            ];
            session(['userInfo' => $userInfo]);
            return redirect('/address');
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'错误信息：'.$res['errmsg'];
        }
    }
}
