<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>
<title>自定义菜单</title>
<!-- 自定义样式 -->
<link rel="stylesheet" href="{{url('css/wx-custom.css')}}">
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<style>
  .layui-input-block{
    margin-left:0;
  }
  .layui-form-switch{
    height: 23px;
    line-height: 23px;
    width:53px;
  }
  .layui-table td, .layui-table th{
    padding: 4px 15px;
  }
</style>
<div class="container">
<div class="custom-menu-edit-con" style="margin-right:3%;">
  <div class="hbox" style="position:absolute;">
    <div class="inner-left">
      <div class="custom-menu-view-con">
        <div class="custom-menu-view">
          <div class="custom-menu-view__title">H-Zeus-菜单展示</div>
          <div class="custom-menu-view__body">
            <div class="weixin-msg-list">
              <ul class="msg-con"></ul>
            </div>
          </div>
          <div id="menuMain" class="custom-menu-view__footer">
            <div class="custom-menu-view__footer__left"></div>
            <div class="custom-menu-view__footer__right"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 右侧信息填写 -->
    <div class="inner-right">
    <table class="layui-table" lay-skin="line" style="margin:0" lay-size="sm">
      <colgroup>
        <col>
        <col>
        <col>
      </colgroup>
      <thead>
        <tr>
          <th width="15%">菜单名称</th>
          <th width="12%">类型</th>
          <th>key</th>
          <th>url</th>
          <th>状态</th>
        </tr> 
      </thead>
      <tbody>
        @foreach($info as $k=>$v)
        <tr>
          <td>
            @if($v->pid != 0)
            &ensp;&ensp;└─&ensp;
            @endif
            {{$v->name}}
          </td>
          <td>{{$v->type}}</td>
          <td>{{$v->key}}</td>
          <td>{{$v->url}}</td>
          <td class="layui-form">
            <div class="layui-form-item" style="margin:0;">
              <div class="layui-input-block">
                @if($v->status == 1)
                <input type="checkbox" disabled checked name="open" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
                @else
                <input type="checkbox" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
                @endif
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div style="bottom:0;position:absolute;width:100%;">
    <a href="{{url('admin/editmenu')}}" class="layui-btn layui-btn-primary" style="width:100%">编辑</a>
    </div>
  </div>
  </div>
</div>
</div>

<!-- 自定义菜单排序 -->
<script>
  var obj = {!!$data!!}; //由控制器传过来
  //显示自定义按钮组
  var button = obj.menu.button; //一级菜单[]
  var menu = '<div class="custom-menu-view__menu"><div class="text-ellipsis"></div></div>'; //显示小键盘
  var customBtns = $('.custom-menu-view__footer__right'); //显示菜单
  showMenu();
  //显示第一级菜单
  function showMenu() {
    if (button.length == 1) {
      appendMenu(button.length);
      showBtn();
      $('.custom-menu-view__menu').css({
        width: '100%',
      });
    }
    if (button.length == 2) {
      appendMenu(button.length);
      showBtn();
      $('.custom-menu-view__menu').css({
        width: '50%',
      });
    }
    if (button.length == 3) {
      appendMenu(button.length);
      showBtn();
      $('.custom-menu-view__menu').css({
        width: '33.3333%',
      });
    }
  }
  //显示子菜单
  function showBtn() {
    for (var i = 0; i < button.length; i++) {
      var text = button[i].name;
      var list = document.createElement('ul');
      list.className = "custom-menu-view__menu__sub";
      $('.custom-menu-view__menu')[i].childNodes[0].innerHTML = text;
      $('.custom-menu-view__menu')[i].appendChild(list);
      for (var j = 0; j < button[i].sub_button.length; j++) {
        var text = button[i].sub_button[j].name;
        var li = document.createElement("li");
        var tt = document.createTextNode(text);
        var div = document.createElement('div');
        li.id = 'sub_' + i + '_' + j; //设置二级菜单id
        div.appendChild(tt);
        li.appendChild(div);
        $('.custom-menu-view__menu__sub')[i].appendChild(li);
      }
    }
  }
  //显示添加的菜单
  function appendMenu(num) {
    var menuDiv = document.createElement('div');
    var mDiv = document.createElement('div');
    var mi = document.createElement('i');
    mDiv.appendChild(mi);
    menuDiv.appendChild(mDiv)
    switch (num) {
      case 1:
        customBtns.append(menu);
        customBtns.append(menuDiv);
        break;
      case 2:
        customBtns.append(menu);
        customBtns.append(menu);
        customBtns.append(menuDiv);
        break;
      case 3:
        customBtns.append(menu);
        customBtns.append(menu);
        customBtns.append(menu);
        break;
    }
  }
</script>
<script>
layui.use(['form'],function(){
  var form = layui.form;
})
</script>