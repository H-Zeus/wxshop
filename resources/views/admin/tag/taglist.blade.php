<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>
<title>标签管理</title>
<!-- 自定义样式 -->
<link rel="stylesheet" href="{{url('css/wx-custom.css')}}">
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<style>
  .layui-table th {
    text-align: center;
  }
  .hbox {
    height: 874px;
  }
  .center {
    text-align:center;
  }
</style>
<body onselectstart="return false">
<div class="container" class="layui-form">
  <div class="custom-menu-edit-con" style="margin-right:3%;">
    <div class="hbox" style="border:1px solid #dee5e7;position:absolute;">
      <table class="layui-table" style="margin:0">
        <tr>
            <th>id</th>
            <th>标签名</th>
            <th>粉丝数</th>
            <th>操作</th>
        </tr>
        @foreach($info as $v)
        <tr>
          <td>{{$v['id']}}</td>
          <td>{{$v['name']}}</td>
          <td class="center">{{$v['count']}}</td>
          <td class="center">
            <button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-danger del" tagid="{{$v['id']}}">删除</button>
            <button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-normal">修改</button>
          </td>
        </tr>
        @endforeach
      </table>
      <div style="bottom:0;position:absolute;width:100%;">
      <button class="layui-btn" id="add">
        <i class="layui-icon">&#xe608;</i> 添加新标签
      </button>
      </div>
    </div>
  </div>
</div>
<script>
  $(function(){
    layui.use(['form','layer'], function() {
      var form = layui.form;
      var layer = layui.layer;
      //删除标签
      $('.del').click(function(){
        var tagid = $(this).attr('tagid');
        $.ajax({
          url:'/admin/tagdel',
          type:'post',
          data:{_token:'{{csrf_token()}}',tagId:tagid}
        }).done(function(msg){
          if(msg == '删除成功'){
            layer.msg(msg,{time:1200},function(){
              history.go(0);
            });
          }else{
            layer.msg(msg,{time:3000})
          }
        })
      })
      //创建新标签
      $('#add').click(function(){
        layer.open({
          type: 2
          ,title: false //不显示标题栏
          ,content: '/admin/tagadd'
          ,area: ['300px', '160px'] //宽高
          ,closeBtn: 2 //关闭按钮
          ,anim: 5 //弹出动画
          ,resize: true //不允许拉伸
        });
      })
    })
  })
</script>