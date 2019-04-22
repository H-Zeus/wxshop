@extends('master')
@section('title','注册')
<meta content="app-id=984819816" name="apple-itunes-app" />
<link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<style>
    .sendcode{
        background-position: 0px -62px;
        position: absolute;
        top: 13px;
        right: 14px;
        background: url(../images/register.png);
        background-size: 100% 382%;
        width: 18px;
        height: 18px;
    }
</style>
<body>
@section('content') 
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">注册</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
    </div>
    <div class="wrapper">
        <input name="hidForward" type="hidden" id="hidForward" />
        <div class="registerCon">
            <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
            <ul>
                <li class="accAndPwd">
                    <dl>
                        <s class="phone"></s>
                        <input id="userMobile" type="text" placeholder="请输入您的手机号码或邮箱号" name="user_tel" />
                        <span class="clear">x</span>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input class="pwd" type="text" placeholder="6-16位数字、字母组成" id="user_pwd" name="user_pwd" />
                        <input class="pwd" type="password" placeholder="6-16位数字、字母组成" style="display: none" />
                        <span class="mr clear">x</span>
                        <s class="eyeclose"></s>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input class="conpwd" type="text" placeholder="请确认密码"/>
                        <input class="conpwd" type="password" placeholder="请确认密码" style="display: none" />
                        <span class="mr clear">x</span>
                        <s class="eyeclose"></s>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input id="keycode" maxlength="4" type="text" placeholder="请输入验证码" name="code" />
                        <a href="javascript:void(0);" class="sendcode" title="获取"></a>
                    </dl>

                    <dl class="a-set">
                        <i class="gou"></i><p>我已阅读并同意《购物协议》</p>
                    </dl>
                </li>
                <li><a id="btnNext" href="javascript:;" class="orangeBtn loginBtn">下一步</a></li>
            </ul>
        </div>   
        <center><div id="replace" style="color:red;font-size:20px"></div></center>
@endsection
</body>
@section('my-js')
<script src="{{url('layui/layui.js')}}"></script> 
<script>
    $('.registerCon input').bind('keydown',function(){
        var that = $(this);
        if(that.val().trim()!=""){
            
            that.siblings('span.clear').show();
            that.siblings('span.clear').click(function(){
                console.log($(this));
                
                that.parents('dl').find('input:visible').val("");
                $(this).hide();
            })

        }else{
           that.siblings('span.clear').hide();
        }

    })
    function show(){
        if($('.registerCon input').attr('type')=='password'){
            $(this).prev().prev().val($("#passwd").val()); 
        }
    }
    function hide(){
        if($('.registerCon input').attr('type')=='text'){
            $(this).prev().prev().val($("#passwd").val()); 
        }
    }
    $('.registerCon s').bind({click:function(){
        if($(this).hasClass('eye')){
            $(this).removeClass('eye').addClass('eyeclose');
            
            $(this).prev().prev().prev().val($(this).prev().prev().val());
            $(this).prev().prev().prev().show();
            $(this).prev().prev().hide();

           
        }else{
                console.log($(this  ));
                $(this).removeClass('eyeclose').addClass('eye');
                $(this).prev().prev().val($(this).prev().prev().prev().val());
                $(this).prev().prev().show();
                $(this).prev().prev().prev().hide();

             }
         }
     })

    function registertel(){
        // 手机号失去焦点
        $('#userMobile').blur(function(){
            // reg=/^1(3[0-9]|4[57]|5[0-35-9]|8[0-9]|7[06-8])\d{8}$/;//验证手机正则(输入前7位至11位)  
            var that = $(this);
          
            if( that.val()==""|| that.val()=="请输入您的手机号或邮箱号")  
            {   
                layer.msg('请输入您的手机号或邮箱号！');
            }  
            // else if(that.val().length<11)  
            // {     
            //     layer.msg('您输入的手机号长度有误！'); 
            // }  
            // else if(!reg.test($("#userMobile").val()))  
            // {   
            //     layer.msg('您输入的手机号不存在!'); 
            // }  
            else if(that.val().length == 11){
                // ajax请求后台数据
            }
        })
        // 密码失去焦点
        $('.pwd').blur(function(){
            reg=/^[0-9a-zA-Z]{6,16}$/;
            var that = $(this);
            if( that.val()==""|| that.val()=="6-16位数字或字母组成")  
            {   
                layer.msg('请设置您的密码！');
            }else if(!reg.test($(".pwd").val())){   
                layer.msg('请输入6-16位数字或字母组成的密码!'); 
            }
        })

        // 重复输入密码失去焦点时
        $('.conpwd').blur(function(){
            var that = $(this);
            var pwd1 = $('.pwd').val();
            var pwd2 = that.val();
            if(pwd1!=pwd2){
                layer.msg('您俩次输入的密码不一致哦！');
            }
        })

    }
        registertel();
    // 购物协议
    $('dl.a-set i').click(function(){
    	var that= $(this);
    	if(that.hasClass('gou')){
    		that.removeClass('gou').addClass('none');
    		$('#btnNext').css('background','#ddd');

    	}else{
    		that.removeClass('none').addClass('gou');
    		$('#btnNext').css('background','#f22f2f');
    	}

    })
    // 下一步提交
    $('#btnNext').click(function(){
    	if($('#userMobile').val()==''){
    		layer.msg('请输入您的手机号或邮箱号！');
    	}else if($('.pwd').val()==''){
    		layer.msg('请输入您的密码!');
    	}else if($('.conpwd').val()==''){
    		layer.msg('请您再次输入密码！');
    	}else if($('#keycode').val()==''){
    		layer.msg('请输入验证码！');
    	}
    })


</script>
<script src="{{url('js/all.js')}}"></script>
<script>
    $(function(){
        var status = true;
        var _token = $('#_token').val();
        //获取验证码
        $('.sendcode').click(function(){
            var user_tel = $('#userMobile').val();
            var user_pwd = $('#user_pwd').val();
            var keycode = $('#keycode').val();
            $.ajax({
                url:"/register/sendsms",
                type:'post',
                data:{_token:_token,user_tel:user_tel,user_pwd:user_pwd},
            }).done(function(res){
                layer.msg(res);
                if(res == '发送成功'){
                    status = true;
                }
            })
        })


        //注册
        $('#btnNext').click(function(){
            var user_tel = $('#userMobile').val();
            var user_pwd = $('#user_pwd').val();
            var keycode = $('#keycode').val();
            if(status == true){
                $.ajax({
                    url:"register",
                    type:'post',
                    data:{_token:_token,user_tel:user_tel,user_pwd:user_pwd,keycode:keycode},
                }).done(function(res){
                    $('#replace').empty();
                    if(res.search('<br>') != -1){
                        layer.msg('！！请规范操作！！');
                        $('#replace').append(res);
                    }else{
                        layer.msg(res,{time:1500},function(){
                        if(res == '注册成功'){
                            location.href="/";
                        }
                    });
                    }
                })
            }
        })
    })
</script>
@endsection