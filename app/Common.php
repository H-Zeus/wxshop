<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tools\sms\lib\Ucpaas;
use App\Model\Cart;

class Common extends Model
{
    /** 发送验证码 */
    public static function SendSms($address,$code)
    {
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid']=env('MOBILE_OPTIONS_ACCOUNTSID');
        //填写在开发者控制台首页上的Auth Token
        $options['token']=env('MOBILE_OPTIONS_TOKEN');

        //初始化 $options必填
        $appid = env('MOBILE_APPID');	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = env('MOBILE_TEMPLATEID');    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID


        //以下是发送验证码的信息
        $param = $code; //验证码 多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $address; // 手机号
        $uid =  config('sms.sms_uid');
        $ucpass = new Ucpaas($options);
        $result = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid);

        if($result) {
            return true;
        }else{
            return false;
        }
    }
    /**
     * @content 生成随机验证码
     * @param int $len 需要生成验证码的长度
     * @return string $code 生成的验证码
     */
    public static function createcode($len)
    {
        $code = '';
        for($i=1;$i<=$len;$i++){
            $code .=mt_rand(0,9);
        }
        return $code;
    }

    /**
     * @content 查询是否超出库存
     * @param int $cart_id 购物车id  用于查出商品id
     * @param int $buy_number 要购买的数量
     * @return 操作结果
     */
    public static function inventory($cart_id,$buy_number)
    {
        $cart_model = new Cart;
        $goods_num = $cart_model
                    ->where(['cart_id'=>$cart_id])
                    ->select('goods_num')
                    ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                    ->first()['goods_num'];
        if($buy_number > $goods_num){
            return '操作错误';
        }else{
            return '操作正常';
        }
    }
}
