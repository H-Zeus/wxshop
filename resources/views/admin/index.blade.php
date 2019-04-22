<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>后台管理</title>
  <meta name="renderer" content="webkit|ie-comp|ie-stand">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <!-- 页面图标 -->
  <link rel="shortcut icon" href="{{url('/uploads/favicon.ico')}}" type="image/x-icon" />
  <link rel="stylesheet" href="{{url('/Ladmin/font.css')}}">
  <link rel="stylesheet" href="{{url('/Ladmin/xadmin.css')}}">
  <script src="{{url('/Ladmin/jquery.min1.js')}}"></script>
  <script src="{{url('/Ladmin/layui.js')}}" charset="utf-8"></script>
  <script type="text/javascript" src="{{url('/Ladmin/xadmin.js')}}"></script>
</head>
<style>
  #test {
    background: none;
    border: none;
    color: #fff;
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    display: inline-block;
    margin-top: 5.5px;
  }
  option {
    color: #fff;
    background: #111;
  }
</style>

<body>
  <div class="container">
    <div class="logo"><a href="javascript:void(0);">L-admin</a></div>
    <div class="left_open">
      <i title="展开左侧栏" class="iconfont">&#xf0185;</i>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
      <li class="layui-nav-item" style="list-style: none">
        <a href="{{url('/admin/laravel')}}" target="myFrameName" style="margin-left:10px">主页</a>
      </li>
    </ul>
    <div style="float:right;margin-right:10px;color:#fff;margin:18px 35px 0 -32px;">
      <i class="iconfont">&#xf00b0;</i>
    </div>
    <ul class="layui-nav right" lay-filter="" style="float:right">
      <select name="" id="test">
        <option selected hidden id="xz">Zeus</option>
        <option value="个人信息">个人信息</option>
        <option value="切换帐号">切换帐号</option>
        <option value="退出">退出</option>
      </select>
    </ul>
  </div>
  <div class="left-nav">
    <div id="side-nav">
      <ul id="nav">
        <li>
          <a href="javascript:;">
            <i class="iconfont">&#xf0161;</i>
            <span>自定义回复消息</span>
            <i class="iconfont nav_right">&#xf0170;</i>
          </a>
          <ul class="sub-menu">
            <li><a href="{{url('/admin/textmessage')}}" target="myFrameName"><i class="iconfont">&#xf0042;</i><span>文本消息</span></a></li>
            <li><a href="{{url('/admin/mixedmessage?image')}}" target="myFrameName"><i class="iconfont">&#xf0044;</i><span>图片消息</span></a></li>
            <li><a href="{{url('/admin/mixedmessage?voice')}}" target="myFrameName"><i class="iconfont">&#xf0147;</i><span>语音消息</span></a></li>
            <li><a href="{{url('/admin/mixedmessage?video')}}" target="myFrameName"><i class="iconfont">&#xf0162;</i><span>视频消息</span></a></li>
            <li><a href="{{url('/admin/mixedmessage?music')}}" target="myFrameName"><i class="iconfont">&#xf0064;</i><span>音乐消息</span></a></li>
            <li><a href="{{url('/admin/newsmessage')}}" target="myFrameName"><i class="iconfont">&#xf0198;</i><span>图文消息</span></a></li>
            <li><a href="{{url('/admin/settype')}}" target="myFrameName"><i class="iconfont">&#xf013e;</i><span>设置消息类型</span></a></li>
          </ul>
        </li>
        <li>
          <a href="{{url('/admin/customize')}}" target="myFrameName"><i class="iconfont">&#xf01ff;</i><span>自定义菜单</span></a>
        </li>
        <li>
          <a href="{{url('/admin/groupsent')}}" target="myFrameName"><i class="iconfont">&#xf0026;</i><span>群发消息</span></a>
        </li>
        <li>
          <a href="javascript:;">
            <i class="iconfont">&#xf012d;</i>
            <span>用户管理</span>
            <i class="iconfont nav_right">&#xf0170;</i>
          </a>
          <ul class="sub-menu">
            <li><a href="{{url('/admin/usercontrol')}}" target="myFrameName"><i class="iconfont">&#xf0161;</i><span>用户列表</span></a></li>
            <li><a href="{{url('/admin/taglist')}}" target="myFrameName"><i class="iconfont">&#xf0060;</i><span>标签管理</span></a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- 备案号 -->
    <div class="footer">
      <div class="copyright"><a href="http://www.miitbeian.gov.cn/" style="color:#fff">京ICP备19005720号</a></div>
    </div>
  </div>

  <div class="page-content">
    <div class="layui-tab-content">
      <div class="layui-tab-item layui-show">
        <iframe src="{{url('/admin/laravel')}}" name="myFrameName" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
      </div>
    </div>
  </div>

</body>
<script>
  $(function() {
    $('#test').change(function() {
      var _this = $(this);
      if (_this.val())
        $('#xz').prop('selected', true);
    })

    //左侧栏 缩进
    $('.left_open i').click(function() {
      if ($(this).text() == '󰆅') {
        $(this).text('󰆄');
      } else {
        $(this).text('󰆅');
      }
    })


  })
</script>

</html>