<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>修改支付密码</title>
<meta content="app-id=984819816" name="apple-itunes-app" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/findpwd.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<link rel="stylesheet" href="{{url('css/modipwd.css')}}">
<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
</head>
<body>    
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">修改登录密码</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
    </div>
    <div class="wrapper">
        <div class="registerCon regwrapp">
            <input type="hidden" id="_token" value="{{csrf_token()}}">
            <div class="account">
                <em>账户名：</em> <i>{{$userInfo->user_tel}}</i>
            </div>
            <div><em>当前密码</em><input type="password" id="old_pwd" value=""></div>
            <div><em>新密码</em><input type="password" id="user_pwd" placeholder="请输入6-16位数字、字母组成的新密码" style="width:70%"></div>
            <div><em>确认新密码</em><input type="password" id="user_repwd" placeholder="确认新密码"></div>
            <div class="save"><span>保存</span></div>
        </div>
    </div>
    <div id="replace" style="color:red;font-size:20px;text-align:center"></div>
<script src="{{url('layui/layui.js')}}"></script>
<script>
    layui.use(['layer'], function(){
        var _token = $('#_token').val();
        //提交数据
        $('.save').click(function(){
            var old_pwd = $('#old_pwd').val();
            var user_pwd = $('#user_pwd').val();
            var user_repwd = $('#user_repwd').val();
            //验证
            if(old_pwd == ''){layer.msg('请输入当前密码');$('#old_pwd').focus();return false;}
            if(user_pwd == ''){layer.msg('请输入新密码');$('#user_pwd').focus();return false;}
            var reg = /^[a-zA-Z0-9]{6,16}$/;
            if(!reg.test(user_pwd)){
                layer.msg("请输入6-16位数字、字母组成的新密码");
                $('#user_pwd').val('');
                $('#user_repwd').val('');
                $('#user_pwd').focus();
                return false;
            }
            if(user_repwd == ''){layer.msg('请确认新密码');$('#user_repwd').focus();return false;}
            if(user_pwd !== user_repwd){layer.msg('新密码与确认密码不一致');$('#user_repwd').focus();return false;}
            $.ajax({
                url:"{{url('/set/safeset/loginpwd')}}",
                type:'post',
                data:{_token:_token,old_pwd:old_pwd,user_pwd:user_pwd,user_repwd:user_repwd}
            }).done(function(res){
                $('#replace').empty();
                if(res.search('<br>') != -1){
                    layer.msg('！！请规范操作！！');
                    $('#replace').append(res);
                }else{
                    layer.msg(res,{time:1000},function(){
                    if(res == '修改成功'){
                        location.href="{{url('/set/safeset')}}";
                    }
                    });
                }
            })
        })
    });
</script>
</body>
</html>