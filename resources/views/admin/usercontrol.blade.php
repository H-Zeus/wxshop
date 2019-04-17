<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('layui1/layui.js')}}"></script>
<title>用户管理</title>
<!-- 自定义样式 -->
<link rel="stylesheet" href="{{url('css/wx-custom.css')}}">
<link rel="stylesheet" href="{{url('layui1/css/layui.css')}}">
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
  img {
    width:48px;
    float: left;
    margin-right:20px;
  }
</style>
<body onselectstart="return false">
<div class="container">
  <div class="custom-menu-edit-con" style="margin-right:3%;">
    <div class="hbox" style="border:1px solid #dee5e7;position:absolute;">
      <table class="layui-table" style="margin:0">
        <tr>
            <th></th>
            <th>openid</th>
            <th>昵称</th>
            <th>性别</th>
            <th>地区</th>
            <th>关注时间</th>
            <th>标签</th>
            <th>关注来源</th>
        </tr>
        @foreach($info as $v)
        <tr>
            <td class="center"><input type="checkbox"></td>
            <td>{{$v['openid']}}</td>
            <td>
              <img src="{{$v['headimgurl']}}">
              <p>
                {{$v['nickname']}}<br>
                @if($v['remark'] != '')
                  ({{$v['remark']}})
                @endif
              </p>
            </td>
            <td class="center">{{$v['sex']}}</td>
            <td>{{$v['country']}}{{$v['province']}}{{$v['city']}}</td>
            <td class="center">{{date('Y-m-d H:i:s',$v['subscribe_time'])}}</td>
            <td>
              <select class="tag">
                @foreach($tagInfo as $value)
                  @if($value['id'] == $v['groupid'])
                    <option value="{{$value['id']}}" selected>{{$value['name']}}</option>
                  @else
                    <option value="{{$value['id']}}">{{$value['name']}}</option>
                  @endif
                @endforeach
              </select>
            </td>
            <td>{{$v['subscribe_scene']}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
<script>
  $(function(){
    layui.use(['form','table'], function() {
      var form = layui.form;
      //修改用户标签
      $('.tag').change(function(){
        var openid = $(this).parent('td').prev().prev().prev().prev().prev().text();
        var id = $(this).val();
        layer.msg('您确认要修改标签嘛',{
          time:false,
          btn:['确定','取消'],
          yes:function(){
            $.ajax({
              url:'/admin/usertagupd',
              type:'post',
              data:{_token:'{{csrf_token()}}',id:id,openid:openid}
            }).done(function(msg){
              layer.closeAll();
              if(msg == '修改成功'){
                layer.msg(msg,{time:1200},function(){
                  history.go(0);
                });
              }else{
                layer.msg(msg,{time:3000})
              }
            })
          },btn2:function(){history.go(0);}
        });
      })
    })
  })
</script>