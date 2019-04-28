<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
  <title>微信扫码登录</title>
</head>
<style>
  p {
    margin-top: 20px;
  }
</style>
<body>
  <center>
    <img src="/qrcode.png" width="90%">
    <p>等待扫码</p>
    <p class="lose"></p>
  </center>
</body>
</html>
<script>
  $(document).ready(function(){
    time = setInterval(getstatus,3000);
    num = 120;
    times = setInterval(lose,1000);
  })
  //二维码失效
  function lose(){
    if(num > 0){
      var str = "二维码还有"+num+"s后失效，请尽快操作";
      $('.lose').html(str);
      num--;
    }else{
      $('img').attr('src','/losecode.png');
      $('.lose').html('');
      $('p').html('');
      clearInterval(time);
      clearInterval(times);
    }
  }

  //获取状态
  function getstatus(){
    $.ajax({
      url:'/user/status?'+Math.random(),
      data:{_token:'{{csrf_token()}}',userid:'{{$userid}}'}
    }).done(function(msg){
      if(msg == 2){
        var str = "已扫码，等待确认";
      }else if(msg == 3){
        location.href = '/';
      }
      $('p').html(str);
    })
  }
  
</script>