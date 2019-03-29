<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>结算支付</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{url('css/cartlist.css')}}">
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
</head>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">结算支付</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
<div>
        <div class="g-pay-lst">
            <ul>
                <li>
                    <a href="javascript:void(0);">
                        <span><img src="/uploads/goodsimg/{{$goodsInfo->goods_img}}" border="0" alt=""></span>
                        <dl>
                            <dt>
                                {{$goodsInfo->goods_name}}
                            </dt>
                            <dd>
                                单价：￥<em class="price">{{$goodsInfo->self_price}}</em>
                                <em style="float:right;line-height:21px" class="number">{{$goodsInfo->buy_number}}</em>
                                <em style="float:right">购买数量：</em>
                            </dd>
                        </dl>
                    </a>
                </li>
            </ul>
            <div id="divMore">  
            </div>
            <p class="gray9">总需支付金额：<em class="orange"><i>￥</i>0</em></p>
        </div>

        <div class="other_pay marginB">
            <a href="javascript:;" class="method leftmoney">
            	<i></i>账户总额：<span class="gray9">(￥<em>0.00</em>)</span><em class="orange fr"></em>
            </a>
            <a href="javascript:;" class="wzf checked">
            	<b class="z-set"></b>第三方支付<em class="orange fr"><span class="colorbbb">需要支付&nbsp;</span><b>￥</b>0</em>
            </a>
            <div class="net-pay">
                <a href="javascript:;" class="checked" id="jdPay">
                	<span class="zfb"></span>
                	<b class="z-set"></b>
                </a>
                <a href="javascript:;" id="jdPay">
                	<span class="kq"></span>
                	<b class="z-set"></b>
                </a>
            </div>
            <div class="paylip">买买买！！</div>
        </div>
        <div class="g-Total-bt">
            <dd><a id="btnPay" href="javascript:;" class="orangeBtn fr w_account">立即支付</a></dd>
        </div> 

        <div class="paywrapp" style="display: none">
            <span class="lip">请输入支付密码</span>    
            <span class="title">支付金额</span>
            <span class="money">￥<i>1.00</i></span>
            <form action="" method="post" name="payPassword" id="form_paypsw">
                <div id="payPassword_container" class="alieditContainer clearfix" data-busy="0">
                    <div class="i-block" data-error="i_error">
                        <div class="i-block six-password">
                            <input class="i-text sixDigitPassword" id="payPassword_rsainput" type="password" autocomplete="off" required="required" value="" name="payPassword_rsainput" data-role="sixDigitPassword" tabindex="" maxlength="6" minlength="6" aria-required="true">
                            <div tabindex="0" class="sixDigitPassword-box" style="width:99%;">
                                <i style="width: 16%; border-color: transparent;" class=""><b style="visibility: hidden;"></b></i>
                                <i style="width: 16%;" class=""><b style="visibility: hidden;"></b></i>
                                <i style="width: 16%;" class=""><b style="visibility: hidden;"></b></i>
                                <i style="width:16%;" class=""><b style="visibility: hidden;"></b></i>
                                <i style="width: 16%;" class=""><b style="visibility: hidden;"></b></i>
                                <i style="width: 16%;" class=""><b style="visibility: hidden;"></b></i>
                                <!-- <span style="width: 16%; left: 285px; visibility: hidden;" id="cardwrap" data-role="cardwrap"></span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="submit">
                <input type="submit" value="取消" class="button  cancel" id="cancelbtn">
                <input type="submit" value="确定" class="button" id="subbtn">
            </div>
        </div>
            

<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script src="{{url('js/all.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>


<script>
	
	$(document).ready(function(){
		var total=0;
		var buy_number= '';
		// console.log($('.g-pay-lst li').length); //获取一共有几条商品数据
		for(var i = 0;i<$('.g-pay-lst li').length;i++){
			total +=parseInt($('.g-pay-lst li').eq(i).find('dd em.price').text())*parseInt($('.g-pay-lst li').eq(i).find('dd em.number').text());
            buy_number += $('.g-pay-lst li').eq(i).find('dd em.number').text()+',';
        }
        // console.log(total); //总金额
		$('.gray9 .orange').html('<i>￥</i>'+total.toFixed(2));//修改总需支付金额显示数量
		$('.wzf .orange').html('<span class="colorbbb">需要支付&nbsp;</span><b>￥</b>'+total.toFixed(2));//修改需要支付显示数量
		$('.money').children('i').html(total.toFixed(2));//修改立即支付显示数量
		// 获取账户总额
        var leftmoney =parseInt($('.other_pay .leftmoney span.gray9 em').text());
        
        //修改 第三方支付 点击状态
		$('.net-pay a').click(function(){
			if($(this).hasClass('checked')){
				
			}else{
                $(this).addClass('checked').siblings('a').removeClass('checked');
                //将余额支付的选中取消
                $('.other_pay a.method').children('i').removeClass('z-set');
			}
		})
        //修改 余额支付 点击状态
		$('.other_pay a.method').click(function(){
			if($(this).children('i').hasClass('z-set')){

			}else{
                $('.net-pay a').removeClass('checked').siblings('a').removeClass('checked');
                //将第三方支付的选中取消
                $(this).children('i').addClass('z-set');
			}
		})

        layui.use(['layer'],function(){
            $('#btnPay').click(function(){
                var payway = $("a[class='checked']").children('span').prop('class');//获取支付方式
                if(payway == 'zfb'){
                    $.ajax({
                        url:"{{url('nowpay')}}",
                        type:'post',
                        data:{_token:'{{csrf_token()}}',total:total,cart_id:'{{$id}}',buy_number:buy_number}
                    }).done(function(res){
                        layer.msg(res,{time:1000},function(){
                            if(res == '请求成功'){
                                layer.msg('页面跳转中，请等待片刻···',{time:1200},function(){
                                    $.ajax({
                                        url:"{{url('alipay/mobilepay')}}",
                                        type:'post',
                                        data:{_token:'{{csrf_token()}}',price:total}
                                    }).done(function(msg){
                                        layer.msg(msg+'正在跳转');
                                    })
                                })
                            }
                        });
                    })
                }else{
                    layer.msg('<center>小店目前仅支持支付宝支付🙏🙏<br>给您带来的不便🙏🙏<br>敬请谅解🙏🙏</center>');
                }
            })
        })
	})
    // // 密码框
        // var payPassword = $("#payPassword_container"),
        // _this = payPassword.find('i'),  
        // k=0,j=0,
        // password = '' ,
        // _cardwrap = $('#cardwrap');
        // //点击隐藏的input密码框,在6个显示的密码框的第一个框显示光标
        // payPassword.on('focus',"input[name='payPassword_rsainput']",function(){
        //     var _this = payPassword.find('i');
        //     if(payPassword.attr('data-busy') === '0'){ 
        //     //在第一个密码框中添加光标样式
        //        _this.eq(k).addClass("active");
        //        _cardwrap.css('visibility','visible');
        //        payPassword.attr('data-busy','1');
        //     }
            
        // }); 
        // //change时去除输入框的高亮，用户再次输入密码时需再次点击
        // payPassword.on('change',"input[name='payPassword_rsainput']",function(){
        //     _cardwrap.css('visibility','hidden');
        //     _this.eq(k).removeClass("active");
        //     payPassword.attr('data-busy','0');
        // }).on('blur',"input[name='payPassword_rsainput']",function(){
        //     _cardwrap.css('visibility','hidden');
        //     _this.eq(k).removeClass("active");                  
        //     payPassword.attr('data-busy','0');
            
        // });
        
        // //使用keyup事件，绑定键盘上的数字按键和backspace按键
        // payPassword.on('keyup',"input[name='payPassword_rsainput']",function(e){
        
        // var  e = (e) ? e : window.event;
        
        // //键盘上的数字键按下才可以输入
        // if(e.keyCode == 8 || (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)){
        //         k = this.value.length;//输入框里面的密码长度
        //         l = _this.size();//6
        //         for(;l--;){
        //         //输入到第几个密码框，第几个密码框就显示高亮和光标（在输入框内有2个数字密码，第三个密码框要显示高亮和光标，之前的显示黑点后面的显示空白，输入和删除都一样）
        //             if(l === k){
        //                 _this.eq(l).addClass("active");
        //                 _this.eq(l).find('b').css('visibility','hidden');
                        
        //             }else{
        //                 _this.eq(l).removeClass("active");
        //                 _this.eq(l).find('b').css('visibility', l < k ? 'visible' : 'hidden');
        //             }               
        //         if(k === 6){
        //             j = 5;
        //         }else{
        //             j = k;
        //         }
        //         $('#cardwrap').css('left',j*43+'px');
            
        //         }
        //     }else{
        //     //输入其他字符，直接清空
        //         var _val = this.value;
        //         this.value = _val.replace(/\D/g,'');
        //     }
        // }); 


        // $('#btnPay').click(function(){
        //     layer.open({
        //         type: 1,
        //         title: false,
        //         content: $('.paywrapp')
        //     })
        // })
        
</script>
</body>
</html>
