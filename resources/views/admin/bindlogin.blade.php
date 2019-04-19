  <meta charset="UTF-8">
  <title>绑定用户</title>
  <link rel="stylesheet" href="{{url('css/bindLogin_style.css')}}">
  <meta charset="utf-8">
  <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
  <script src="{{url('layui/layui.js')}}"></script>
<body>
  <main>
    <form class="form">
      <div class="form__cover"></div>
      <div class="form__loader">
        <div class="spinner active">
          <svg class="spinner__circular" viewBox="25 25 50 50">
            <circle class="spinner__path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10">
            </circle>
          </svg>
        </div>
      </div>
      <div class="form__content">
        <h1>Authorization</h1>
        <div class="styled-input">
          <input type="text" class="styled-input__input" id="user">
          <div class="styled-input__placeholder"> <span class="styled-input__placeholder-text">Tel&nbsp;or&nbsp;Email</span> </div>
          <div class="styled-input__circle"></div>
        </div>
        <div class="styled-input" style="width:48%;">
          <input type="text" class="styled-input__input" id="code" AUTOCOMPLETE="OFF">
          <div class="styled-input__placeholder"> <span class="styled-input__placeholder-text">Code</span> </div>
          <div class="styled-input__circle"></div>
        </div>
        <button type="button" id="sendCode" class="styled-button" style="width: 48%;position: absolute;left: 52%;top: 42.2%;">
          <span class="styled-button__real-text-holder"> 
            <span class="styled-button__real-text">Send</span> 
            <span class="styled-button__moving-block face"> 
              <span class="styled-button__text-holder">
                <span class="styled-button__text">Send</span> 
              </span>
            </span>
            <span class="styled-button__moving-block back"> 
              <span class="styled-button__text-holder">
                <span class="styled-button__text">Send</span>
              </span> 
            </span>
          </span>
        </button>
        <!-- <div class="styled-input">
          <input type="password" class="styled-input__input">
          <div class="styled-input__placeholder"> <span class="styled-input__placeholder-text">Password</span> </div>
          <div class="styled-input__circle"></div>
        </div> -->
        <button type="button" id="sub" class="styled-button">
          <span class="styled-button__real-text-holder"> 
            <span class="styled-button__real-text">Submit</span> 
            <span class="styled-button__moving-block face"> 
              <span class="styled-button__text-holder">
                <span class="styled-button__text">Submit</span> 
              </span>
            </span>
            <span class="styled-button__moving-block back"> 
              <span class="styled-button__text-holder">
                <span class="styled-button__text">Submit</span>
              </span> 
            </span>
          </span>
        </button>
        <h5><a href="{{url('/register')}}" style="text-decoration:none;color:azure;">Register</a></h5>
      </div>
    </form>
  </main>
  <script src="{{url('js/bindLogin_index.js')}}"></script>
</body>
<script>
  $(function(){
    layui.use(['layer'],function(){
      //发送验证码
      $('#sendCode').click(function(){
        var user = $('#user').val();
        if(user == ''){layer.msg('请输入账号');$('#user').focus();return false;};
        $.ajax({
          url:'/admin/sendcode',
          type:'post',
          data:{_token:'{{csrf_token()}}',user:user}
        }).done(function(msg){
          layer.msg(msg);
        })
      })
      //点击submit
      $('#sub').click(function(){
        var user = $('#user').val();
        var code = $('#code').val();
        if(user == ''){layer.msg('请输入账号');$('#user').focus();return false;};
        if(code == ''){layer.msg('请输入验证码');$('#code').focus();return false;};
        $.ajax({
          url:'/admin/bindlogin',
          type:'post',
          data:{_token:'{{csrf_token()}}',user:user,code:code,openid:'{{$openid}}'}
        }).done(function(msg){
          layer.msg(msg,{time:1200},function(){
            if(msg == '绑定成功'){
              //自动关闭微信浏览器页面
              WeixinJSBridge.call('closeWindow');
            }
          });
        })
      })
    })
  })
</script>