<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
  <script src="{{url('layui1/layui.js')}}"></script>
  <title>Document</title>
</head>
<body>
  <center>
    <img src="{{$userinfo['headimgurl']}}">
    <br>
    <button>确认登录</button>
  </center>
</body>
</html>
<script>
$(function(){
  layui.use(['layer'],function(){

    $('button').click(function(){
      $.ajax({
        url:'/user/changestatus',
        data:{_token:'{{csrf_token()}}',openid:'{{$userinfo["openid"]}}'}
      }).done(function(msg){
        layer.msg(msg,{time:1500},function(){
          if(msg == '登录成功'){
            WeixinJSBridge.call('closeWindow'); 
          }
        });
      })
    })

  })
})
</script>