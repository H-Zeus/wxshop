<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>编辑收货地址</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{url('css/writeaddr.css')}}">
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{url('dist/css/LArea.css')}}">
</head>

<body>
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">编辑收货地址</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="javascript:void(0);" class="m-index-icon">保存</a>
    </div>
    <div class=""></div>
    <form class="layui-form">
      <div class="addrcon">
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        <input type="hidden" id="address_id" value="{{$addressInfo->address_id}}">
          <ul>
              <li><em>收货人</em><input type="text" id="address_name" value="{{$addressInfo->address_name}}" placeholder="请填写真实姓名"></li>
              <li><em>手机号码</em><input type="number" id="address_tel" value="{{$addressInfo->address_tel}}" placeholder="请输入手机号"></li>
              <li>
                  <em>所在区域</em>
                  <input id="demo1" type="text" readonly="" placeholder="请选择所在区域" value="{{$addressInfo->address}}">
              </li>
              <li class="addr-detail">
                <em>详细地址</em>
                <input type="text" placeholder="20个字以内" id="address_detail" class="addr" value="{{$addressInfo->address_detail}}">
              </li>
          </ul>
      </div>
    </form>
    <!-- SUI mobile -->
    <script src="{{url('dist/js/LArea.js')}}"></script>
    <script src="{{url('dist/js/LAreaData1.js')}}"></script>
    <script src="{{url('dist/js/LAreaData2.js')}}"></script>
    <script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
    <script src="{{url('layui/layui.js')}}"></script>

    <script>
        var address = '';
        var area = new LArea();
        area.init({
            'trigger': '#demo1', //触发选择控件的文本框，同时选择完毕后name属性输出到该位置
            'valueTo': '#value1', //选择完毕后id属性输出到该位置
            'keys': {
                id: 'id',
                name: 'name'
            }, //绑定数据源相关字段 id对应valueTo的value属性输出 name对应trigger的value属性输出
            'type': 1, //数据源类型
            'data': LAreaData //数据源
        });
        area.success = function() {
            address = area.trigger.value;
            // console.log(area.trigger.value.split(','));
        }
        // area.value = [0, 2]; //控制初始位置，注意：该方法并不会影响到input的value
    </script>
</body>
<script>
    $(function() {
        layui.use(['layer'],function(){
          //保存提交
          $('.m-index-icon').click(function(){
              var _token = $('#_token').val();
              var address_id = $('#address_id').val();
              var address_name = $('#address_name').val();
              var address_tel = $('#address_tel').val();
              var address_detail = $('#address_detail').val();
              $.ajax({
                url:'/address/writeaddr/update/'+address_id,
                type:'post',
                data:{
                  _token:_token,
                  address_name:address_name,
                  address_tel:address_tel,
                  address:address,
                  address_detail:address_detail,
                }
              }).done(function(res){
                layer.msg(res,{time:1200},function(){
                  if(res == '保存成功' || res == '数据未编辑'){
                    location.href="{{url('/address')}}";
                  }
                });
              })
          })
        })
    })
</script>

</html> 