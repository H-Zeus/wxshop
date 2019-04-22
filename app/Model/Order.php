<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Tools\email\SMTP;
use App\Tools\email\PHPMailer;
use App\Tools\sms\lib\Ucpaas;

class Order extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'shop_order';

    /**
     * 主键ID
     *
     * @var string
     */
    protected $primaryKey  = 'order_id';


     /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 更改状态显示。
     *
     * @param  string  $value
     * @return string
     */
    public function getOrderStatusAttribute($value)
    {
        if($value == 1){
            return '未支付-等待已支付';
        }else if($value == 2){
            return '已支付-等待确认';
        }else if($value == 3){
            return '已确认-等待备货';
        }else if($value == 4){
            return '备货中-等待发货';
        }else if($value == 5){
            return '发货中';
        }else if($value == 6){
            return '已发货';
        }else if($value == 7){
            return '订单完成';
        }
    }
    
    /**
     * @content 发送短信 
     * @param string $address 手机号
     * @param string $code 验证码
     * */
    public static function sendsms($address,$code){
        //随机生成验证码
        // $code = Common::createcode(4);

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
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 发送邮件
     * @param string $address 收件人邮箱
     * @param string $code 验证码
     */
    public static function sendEmail($address,$code)
    {
        new SMTP;
        //实例化PHPMailer核心类
        $mail = new PHPMailer();
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        // $mail->SMTPDebug =1;
        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth=true;
        //链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.163.com';//163邮箱：smtp.163.com
        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';//163邮箱就注释
        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = 465;//163邮箱：25
        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = 'UTF-8';
        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = 'Daisy';
        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username ='13315080034@163.com';
        //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
        $mail->Password = 'ht865291170';//163邮箱也有授权码 进入163邮箱帐号获取
        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = '13315080034@163.com';
        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);
        
        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($address);
        //添加该邮件的主题
        $mail->Subject = '注册成功';
        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = "验证码为:$code";

        $status = $mail->send();

        //简单的判断与提示信息
        if($status) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * @content 发送订单模板信息
     */
    public static function orderInfo($fromUserName,$keywords)
    {
        $keywords = explode('订单',$keywords)['1'];
        // $data = DB::table('shop_order')
        //     ->where('order_no',$keywords)
        //     ->join('shop_order_detail','shop_order.order_id','=','shop_order_detail.order_id')
        //     ->get();
        $data = DB::table('shop_order')->where('order_no',$keywords)->first();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".Wechat::GetAccessToken();
        switch($data->order_status){
            case "1":
                $status = '未支付';
            break;
            case "2";
                $status = '已支付';
            break;
            case "3";
                $status = '已确认';
            break;
            case "4";
                $status = '备货中';
            break;
            case "5";
                $status = '发货中';
            break;
            case "6";
                $status = '已发货';
            break;
            case "7";
                $status = '订单完成';
            break;
        }
        $postObj = '
            {
                "touser":"'.$fromUserName.'",
                "template_id":"jHaemSceu9ifvaDQts0yJOjE_b1MCBE7bq13q7MSHV4",        
                "data":{
                        "welcome": {
                            "value":"欢迎使用订单查询系统",
                            "color":"#173177"
                        },
                        "orderNo":{
                            "value":"'.$data->order_no.'",
                            "color":"#173177"
                        },
                        "orderAmount": {
                            "value":"'.$data->order_amount.'",
                            "color":"#173177"
                        },
                        "orderStatus": {
                            "value":"'.$status.'",
                            "color":"#173177"
                        }
                }
            }
        ';
        Wechat::HttpPost($url,$postObj);
    }
}
