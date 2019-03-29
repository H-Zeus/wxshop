<?php

namespace App\Http\Controllers;

use App\Tools\alipay\wappay\service\AlipayTradeService;
use App\Tools\alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
class AliPayController extends Controller
{
    /**
     * @content 手机支付
     * @param mixed $ordername 订单名称
     * @param tinyint $price 付款金额
     * @param varchar $order_no 订单号
     */
    public function mobilepay(Request $request)
    {
        header("Content-type: text/html; charset=utf-8");
        $config = config('alipay');
        if($request->post()){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = session('payInfo')['order_no'];

            //订单名称，必填
            $subject = session('payInfo')['order_name'];

            //付款金额，必填
            $total_amount = $request->price;

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;
        }
    }

    public function re()
    {
        $order_id = session('payInfo')['order_id'];
        DB::table('shop_order')->where(['order_id'=>$order_id])->update(['pay_status'=>2,'order_status'=>2]);
        return view('paysuccess');
    }
    public function notify()
    {
        dd('notify');
    }
}
