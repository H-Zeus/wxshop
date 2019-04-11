<!DOCTYPE html>
<html class=" -webkit-">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>radiobox单选框DEMO</title>
<link rel="stylesheet" href="{{url('/radiobox/prefixfree.css')}}">
<script src="{{url('layui/layui.js')}}"></script>
<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('radiobox/prefixfree.min.js')}}"></script>
<!-- <link href="{{url('layui/css/layui.css')}}" rel="stylesheet" type="text/css" /> -->
</head>
<body>
  <div style="color:hsla(0, 0%, 100%, .80);width:200px">
      <label for="text">文本消息</label>    
      <input type="radio" name="name" id="text" value="text"><br>
      <label for="image">图片消息</label>    
      <input type="radio" name="name" id="image" value="image"><br>
      <label for="voice">语音消息</label>    
      <input type="radio" name="name" id="voice" value="voice"><br>
      <label for="video">视频消息</label>    
      <input type="radio" name="name" id="video" value="video"><br>
      <label for="music">音乐消息</label>    
      <input type="radio" name="name" id="music" value="music"><br>
      <label for="news">图文消息</label>    
      <input type="radio" name="name" id="news" value="news"><br>
      <input type="button" style="width:50px;color:azure;" value="提交">
  </div>
</body>
<script>
  $(function(){
    $("input[id='{{$type}}']").prop('checked',true);
    layui.use(['layer'],function(){
      $('input:button').click(function(){
        var type = $('input:radio:checked').val();
        layer.msg("您选择的是 "+type+" 类型,是否确认",{
          time:false, //取消延迟关闭
          btn:['确认','取消'],
          yes:function(){
            $.ajax({
              url:"/admin/settype",
              data:{_token:'{{csrf_token()}}',type:type},
              type:'post',
            }).done(function(msg){
              layer.closeAll();
              layer.msg(msg);
            })
          }
        });
      })
    })
  })
</script>
</html>