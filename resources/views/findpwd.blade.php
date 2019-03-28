<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>找回密码</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/find.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('layui/css/layui.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{url('js/jquery-1.8.3.min.js')}}"></script>
    <script src="{{url('layui/layui.js')}}"></script>
</head>
<body>    
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">找回密码</strong>
        <a href="{{url('/login')}}" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="{{url('/')}}" class="m-index-icon"><i class="home-icon"></i></a>
    </div>

    <div class="wrapper">
        <div class="registerCon">
            <div class="binSuccess5">
                <ul>
                    <li class="accAndPwd">
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
                        <dl class="phone">
                            <div class="txtAccount">
                                <input id="txtAccount" type="text" placeholder="请输入您的手机号"><i></i>
                                <a href="javascript:void(0);" class="sendcode" id="btn">获取验证码</a>
                            </div>
                            <cite class="passport_set" style="margin-right:30%;display: none;"></cite>
                        </dl>
                        <dl>
                            <input id="txtPassword" type="text" placeholder="请输入验证码" value="" maxlength="4" /><b></b>
                        </dl>
                    </li>
                </ul>
                <a id="btnLogin" href="javascript:;" class="orangeBtn loginBtn">下一步</a>
            </div>
        </div>
    </div>
    <div id="replace" style="color:red;font-size:20px;text-align:center"></div>
</body>

<script>
    $(function(){
        layui.use(['layer'],function(){
            var status = false;
            var _token = $('#_token').val();
            var old_tel;
            //获取验证码
            $('.sendcode').click(function(){
                var user_tel = $('#txtAccount').val();
                var keycode = $('#keycode').val();
                old_tel = user_tel;
                //验证
                if(user_tel == ''){layer.msg('请输入手机号');$('#txtAccount').focus();return false;}
                var reg = /^[0-9]{11}$/;
                if(!reg.test(user_tel)){layer.msg('手机号格式错误');$('#txtAccount').focus();return false;}
                $.ajax({
                    url:"/findpwd/sendsms",
                    type:'post',
                    data:{_token:_token,user_tel:user_tel},
                }).done(function(res){
                    layer.msg(res);
                    if(res == '发送成功'){
                        status = true;
                    }
                })
            })

            //提交
            $('#btnLogin').click(function(){
                var keycode = $('#txtPassword').val();
                var user_tel = $('#txtAccount').val();
                status = true;
                if(status == true){
                    //验证
                    if(user_tel !== old_tel){layer.msg('验证手机号错误！');$('#txtAccount').focus();return false;}
                    if(user_tel == ''){layer.msg('请输入手机号');$('#txtAccount').focus();return false;}
                    var reg1 = /^[0-9]{11}$/;
                    if(!reg1.test(user_tel)){layer.msg('手机号格式错误');$('#txtAccount').focus();return false;}
                    if(keycode == ''){layer.msg('请输入验证码');$('#txtPassword').focus();return false;}
                    var reg2 = /^[0-9]{4}$/;
                    if(!reg2.test(keycode)){layer.msg('验证码为4位有效数字');$('#txtPassword').focus();return false;}
                    $.ajax({
                        url:"{{url('findpwd')}}",
                        type:'post',
                        data:{_token:_token,keycode:keycode,user_tel:user_tel},
                    }).done(function(res){
                        $('#replace').empty();
                        if(res.search('<br>') != -1){
                            layer.msg('！！请规范操作！！');
                            $('#replace').append(res);
                        }else{
                            if(res == '验证码正确'){
                                layer.msg('操作成功，正在跳转',{time:1200},function(){                                    
                                    $.ajax({
                                        url:"{{url('resetpassword')}}",
                                        type:'post',
                                        data:{_token:_token,old_tel:old_tel},
                                    }).done(function(msg){
                                        $('body').html(msg);
                                    })
                                })
                            }else{
                                layer.msg(res);
                            }
                        }
                    })
                }
            })
        })
    })
</script>
</html>