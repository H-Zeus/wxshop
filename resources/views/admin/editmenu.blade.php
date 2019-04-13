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
  .layui-table th{
    text-align:center;
  }
  input{
    border:none;
    height:30px;
    display:inline-block
  }
  input:hover{
    background:#f8f8ff;
  }
  select{
    border:none;
    color: #999;
    appearance:none;  
  }
</style>
<div class="container">
<div class="custom-menu-edit-con" style="margin-right:3%;">
  <div class="hbox" style="border:1px solid #dee5e7;height:608px;position:absolute;">
    <table class="layui-table" style="margin:0">
      <colgroup>
        <col>
        <col>
        <col>
      </colgroup>
      <thead>
        <tr>
          <th width="15%">菜单名称</th>
          <th width="12%">类型</th>
          <th width="12%">key</th>
          <th width="35%">url</th>
          <th width="1%">状态</th>
          <th width="20%">操作</th>
        </tr> 
      </thead>
      <tbody>
        @foreach($info as $v)
        <tr>
          <td>
            @if($v->pid != 0)
              &ensp;&ensp;└─&ensp;
            @endif
            <input type="text" class="name" value="{{$v->name}}">
          </td>
          <td>
              <!-- {{$v->type}} -->
              <select name="city" class="stype">
                <option value=""></option>
                @foreach($type as $value)
                  @if($value->type == $v->type)
                    <option value="{{$value->type}}" selected>{{$value->type}}</option>
                  @else
                    <option value="{{$value->type}}">{{$value->type}}</option>
                  @endif
                @endforeach
              </select>
          </td>
          <td>
            <input type="text" value="{{$v->key}}">
          </td>
          <td>
            <input type="text" value="{{$v->url}}">
          </td>
          <td class="layui-form">
            <div class="layui-form-item" style="margin:0;">
              <div class="layui-input-block status" m_id='{{$v->m_id}}' pid='{{$v->pid}}'>
                @if($v->status == 1)
                <input type="checkbox" checked lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
                @else
                <input type="checkbox" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
                @endif
              </div>
            </div>
          </td>
          <td style="text-align:center;">
              @if($v->pid == 0)
              <button class="layui-btn layui-btn-sm layui-btn-normal del" m_id='{{$v->m_id}}' pid='{{$v->pid}}'><i class="layui-icon"></i> 删除</button>
              <button class="layui-btn layui-btn-sm layui-btn-normal add" menu="1" m_id='{{$v->m_id}}' pid='{{$v->pid}}'><i class="layui-icon"></i> 添加</button>
            @else
              <button class="layui-btn layui-btn-sm layui-btn-normal del" menu="2" m_id='{{$v->m_id}}' pid='{{$v->pid}}'><i class="layui-icon"></i> 删除</button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <button class="layui-btn layui-btn-lg layui-btn-radius layui-btn-normal addmenu" style="position:absolute;right:6.5%;margin-top:10px;">添加一级菜单</button>
    <div style="bottom:0;position:absolute;width:100%;">
    <a href="javascript:void(0);" class="layui-btn layui-btn-primary" id="btn" style="width:48%;float: left;margin-left:1%;">保存</a>
    <a href="{{url('admin/customize')}}" class="layui-btn layui-btn-primary" style="width:48%;float:right;margin-right:1%;">返回</a>
    </div>
  </div>
</div>
</div>

<script>
$(function(){
  layui.use(['form','layer'],function(){
    var form = layui.form;
    //预处理
    $('tr').each(function(){
      var _this = $(this);
      if(_this.find('td').next().last().find('.del').attr('pid') == 0 && _this.next().find('td').next().last().find('.del').attr('pid') != 0){
       _this.find('td').first().next().remove();
       _this.find('td').first().next().remove();
       _this.find('td').first().next().html('');
       _this.find('td').first().next().attr('colspan',3);
      }
    })

    //删除
    $(document).on('click','.del',function(){
      var _this = $(this);
      var pid = _this.attr('pid');
      var m_id = _this.attr('m_id');
      var nextDelPid = _this.parents('tr').next().find('td').last().find('.del').attr('pid');
      //判断是否有二级菜单
      if(nextDelPid != 0 && pid == 0){
        layer.msg('当前菜单下有二级菜单<br>将会删除该菜单下所有二级菜单',{time:false,btn:['确认删除','取消'],yes:function(){
          _this.parents('tr').remove();
          $('button[pid='+m_id+']').parents('tr').remove();
          layer.closeAll();
        }});
      }else{
        _this.parents('tr').remove();
      }
    });

    //开关
    $(document).on('click','.status',function(){
      var _this = $(this).find('input');
      var pid = $(this).attr('pid');
      var m_id = $(this).attr('m_id');
      var status = _this.prop('checked');
      if(status == true){
        $('div[pid='+m_id+']').find('input').next('div').addClass('layui-form-onswitch');
        $('div[pid='+m_id+']').find('input').next('div').find('em').text('ON');
      }else{
        $('div[pid='+m_id+']').find('input').next('div').removeClass('layui-form-onswitch');
        $('div[pid='+m_id+']').find('input').next('div').find('em').text('OFF');
      }
    })
    
    //添加 二级菜单
    $(document).on('click','.add',function(){
      var _this = $(this);
      var pid = _this.attr('pid');
      var m_id = _this.attr('m_id');
      //判断二级菜单个数
      var num = $('button[menu=2][pid='+m_id+']').length;
      if(num >= 5){
        layer.msg('二级菜单最多可有5个');
      }else{
        _this.parents('tr').find('td').next().find('select').val(null); //修改一级菜单类型为空
        if(_this.parent().prev().prev().attr('colspan') == null){
          _this.parent().prev().prev().remove();
          _this.parent().prev().prev().remove();
          _this.parent().prev().prev().html('');
          _this.parent().prev().prev().attr('colspan',3);
        }
        // return;
        _this.parents('tr').after(
          '<tr>'+
            '<td>'+
              '&ensp;&ensp;└─&ensp;<input type="text" placeholder="请输入名称">'+
            '</td>'+
            '<td>'+
                '<select name="city" class="stype">'+
                  '@foreach($type as $value)'+
                    '<option value="{{$value->type}}">{{$value->type}}</option>'+
                  '@endforeach'+
                '</select>'+
            '</td>'+
            '<td>'+
              '<input type="text" placeholder="请输入Key">'+
            '</td>'+
            '<td>'+
              '<input type="text" placeholder="请输入Url">'+
            '</td>'+
            '<td class="layui-form">'+
              '<div class="layui-form-item" style="margin:0;">'+
                '<div class="layui-input-block status">'+
                  '<input type="checkbox" checked lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">'+
                '</div>'+
              '</div>'+
            '</td>'+
            '<td style="text-align:center;">'+
              '<button class="layui-btn layui-btn-sm layui-btn-normal del" menu="2" pid='+m_id+'><i class="layui-icon"></i> 删除</button>'+
            '</td>'+
          '</tr>'
          );
        }
    });

    //添加一级菜单
    $(document).on('click','.addmenu',function(){
      //判断一级菜单个数
      var num = $('button[menu=1]').length;
      if(num >= 3){
        layer.msg('一级菜单最多可有3个');
      }else{
        var i = Math.round(Math.random()*100)+99;
        $('tr').last().after(
          '<tr>'+
            '<td>'+
              '<input type="text" placeholder="请输入名称">'+
            '</td>'+
            '<td>'+
                '<select name="city" class="stype">'+
                  '<option value=""></option>'+
                  '@foreach($type as $value)'+
                    '<option value="{{$value->type}}">{{$value->type}}</option>'+
                  '@endforeach'+
                '</select>'+
            '</td>'+
            '<td>'+
              '<input type="text" placeholder="请输入Key">'+
            '</td>'+
            '<td>'+
              '<input type="text" placeholder="请输入Url">'+
            '</td>'+
            '<td class="layui-form">'+
              '<div class="layui-form-item" style="margin:0;">'+
                '<div class="layui-input-block status">'+
                  '<input type="checkbox" checked lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">'+
                '</div>'+
              '</div>'+
            '</td>'+
            '<td style="text-align:center;">'+
              '<button class="layui-btn layui-btn-sm layui-btn-normal del" m_id='+i+' pid="0"><i class="layui-icon"></i> 删除</button>'+
              '<button class="layui-btn layui-btn-sm layui-btn-normal add" menu="1" m_id='+i+' pid='+i+'><i class="layui-icon"></i> 添加</button>'+
            '</td>'+
          '</tr>'
        );
      }
    })

    //保存 提交到控制器
    var name ='';
    var type ='';
    var key ='';
    var url ='';
    var status ='';
    $('#btn').click(function(){
      //获取数据
      $('tr').each(function(){
        name += $(this).find('td').first().find('input').val()+'||'; //菜单名称
        type += $(this).find('td').first().next().find('select').val()+'||'; //类型
        key += $(this).find('td').first().next().next().find('input').val()+'||'; //Key
        url += $(this).find('td').first().next().next().next().find('input').val()+'||'; //Url
        status += $(this).find('td').last().prev().find('input').prop('checked')+'||'; //Status
      })
      $.ajax({
        url:'editmenu',
        data:{name:name,type:type,key:key,url:url,status:status,_token:'{{csrf_token()}}'},
        type:'post'
      }).done(function(msg){
        layer.msg(msg);
      })
    })
  })
})
</script>