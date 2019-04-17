<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>
<title>标签管理</title>
<!-- 自定义样式 -->
<link rel="stylesheet" href="{{url('css/wx-custom.css')}}">
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<link rel="stylesheet" href="{{url('css/Modern/modernforms.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/Modern/font-awesome.4.6.0.css')}}">
<link rel="stylesheet" href="{{url('css/Modern/theme-red.css')}}">
<style>
  body {
    margin:25px 15px;
  }
</style>
<body onselectstart="return false">
  <div class="modern-forms layui-form">
    <div class="field-group">
      <input type="text" id="tagName" class="mdn-input" autocomplete="off" placeholder="请输入标签名">
      <label class="mdn-label">标签名</label>
      <span class="mdn-bar"></span>
    </div>
  </div>
  <button class="layui-btn" id="btn">添加</button>
<script>
  $(function(){
    layui.use(['form','layer'], function() {
      var form = layui.form;
      var layer = layui.layer;
      $('#btn').click(function(){
        var tagName = $('#tagName').val();
        if(tagName == ''){
          layer.msg('标签名不能为空!');
          return false;
        }
        $.ajax({
          url:'/admin/tagadd',
          type:'post',
          data:{_token:'{{csrf_token()}}',tagName:tagName}
        }).done(function(msg){
          layer.msg(msg,{time:1500},function(){
            if(msg == '创建成功'){
              //关闭iframe层 刷新父页面
              var index = parent.layer.getFrameIndex(window.name);
              parent.layer.close(index);
              parent.location.reload(index);
            }
          })
        })
      })
    })
  })
</script>