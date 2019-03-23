@extends('master')
@section('title','登录')
<meta content="app-id=984819816" name="apple-itunes-app" />
<link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<body>
@section('content') 
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">登录</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"><i class="home-icon"></i></a>
    </div>

    <div class="wrapper">
        <div class="registerCon">
            <div class="binSuccess5">
                <ul>
                    <li class="accAndPwd">
                        <dl>
                            <div class="txtAccount">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
                                <input id="txtAccount" type="text" name="user_tel" placeholder="请输入您的手机号码/邮箱"><i></i>
                            </div>
                            <cite class="passport_set" style="display: none"></cite>
                        </dl>
                        <dl>
                            <input id="txtPassword" type="password" placeholder="密码" name="user_pwd" maxlength="20" /><b></b>
                        </dl>
                        <dl>
                            <input id="verifycode" type="text" placeholder="请输入验证码"  maxlength="4" /><b></b>
                            <center>
                                <img src="{{url('/verify/create')}}" id="code" alt="点击刷新" style="width:70%;height:40px;margin-top:14px">
                            </center>
                        </dl>
                    </li>
                </ul>
                <a id="btnLogin" href="javascript:;" class="orangeBtn loginBtn">登录</a>
            </div>
            <div class="forget">
                <a href="javascript:void(0);">忘记密码？</a><b></b>
                <a href="{{url('/register')}}">新用户注册</a>
            </div>
        </div>
    </div>
@endsection
</body>
<script>
    $(function(){
        //刷新验证码
        $('#code').click(function(){
            $(this).attr('src',"{{url('/verify/create')}}"+"?"+Math.random())
        })
        //登录
        $('#btnLogin').click(function(){
            var _token = $('#_token').val();
            var user_tel = $('#txtAccount').val();
            var user_pwd = $('#txtPassword').val();
            var code = $('#verifycode').val();
            $.post(
                "login",
                {_token:_token,user_tel:user_tel,user_pwd:user_pwd,code:code},
                function(res){
                    layer.msg(res,{time:1500},function(){
                        if(res == '登录成功'){
                            location.href="/";
                        }
                    });
                }
            )
        })
    })
</script>