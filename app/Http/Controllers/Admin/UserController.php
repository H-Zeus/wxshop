<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\json_decode;

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
            $userInfo = json_decode(file_get_contents($userInfoUrl),true);
            dd($userInfo);
        }else{
            return '授权失败<br>错误代码：'.$res['errcode'].'错误信息：'.$res['errmsg'];
        }
    }

    /**
     * @content 绑定用户
     */
    public function bindLogin(Request $request)
    {
        return view('admin.bindlogin');
    }
}
