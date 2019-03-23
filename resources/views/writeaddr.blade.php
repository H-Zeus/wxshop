@extends('master')
@section('title','填写收货地址')
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <link rel="stylesheet" href="{{url('css/writeaddr.css')}}">
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{url('dist/css/LArea.css')}}">
@section('content')
<body>
  <!--触屏版内页头部-->
  <div class="m-block-header" id="div-header">
      <strong id="m-title">填写收货地址</strong>
      <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
      <a href="/" class="m-index-icon">保存</a>
  </div>
  <div class=""></div>
  <!-- <form class="layui-form" action="">
    <input type="checkbox" name="xxx" lay-skin="switch">  
    
  </form> -->
  <form class="layui-form">
    <div class="addrcon">
      <ul>
        <li>
          <em>收货人</em>
          <input type="text" name="" placeholder="请填写真实姓名">
        </li>
        <li>
          <em>手机号码</em>
          <input type="number" name="" placeholder="请输入手机号">
        </li>
        <li>
          <em>所在区域</em>
          <select name="quiz1">
            <option value="">请选择省</option>
          </select>
          <select name="quiz2">
            <option value="">请选择市</option>
          </select>
          <select name="quiz3">
            <option value="">请选择县/区</option>
          </select>
        </li>
        <li class="addr-detail">
          <em>详细地址</em>
          <input type="text" placeholder="20个字以内" class="addr">
        </li>
      </ul>
      <div class="setnormal"><span>设为默认地址</span><input type="checkbox" name="xxx" lay-skin="switch">  </div>
    </div>
  </form>
</body>
@endsection
<!-- SUI mobile -->
<script src="{{url('dist/js/LArea.js')}}"></script>
<script src="{{url('dist/js/LAreaData1.js')}}"></script>
<script src="{{url('dist/js/LAreaData2.js')}}"></script>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>

<script>
  //Demo
layui.use('form', function(){
  var form = layui.form();
  
  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});

var area = new LArea();
area.init({
    'trigger': '#demo1',//触发选择控件的文本框，同时选择完毕后name属性输出到该位置
    'valueTo':'#value1',//选择完毕后id属性输出到该位置
    'keys':{id:'id',name:'name'},//绑定数据源相关字段 id对应valueTo的value属性输出 name对应trigger的value属性输出
    'type':1,//数据源类型
    'data':LAreaData//数据源
});


</script>