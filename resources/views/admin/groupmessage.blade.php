<script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('layui/layui.js')}}"></script>
<title>群发消息</title>
<!-- 自定义样式 -->
<link rel="stylesheet" href="{{url('css/wx-custom.css')}}">
<link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
<link rel="stylesheet" href="{{url('css/Modern/modernforms.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/Modern/font-awesome.4.6.0.css')}}">
<link rel="stylesheet" href="{{url('css/Modern/theme-red.css')}}">
<link type="text/css" rel="stylesheet" charset="UTF-8" href="{{url('css/Modern/translateelement.css')}}">
<style>
  .layui-table td,
  .layui-table th {
    padding: 28px 15px;
    border: none;
    color: #8d8d8d;
  }
  .layui-table th {
    text-align: center;
    cursor:pointer;
  }
  .hbox {
    height: 874px;
  }
  th span {
    margin-top: -1px;
    margin-left: 3px;
    position: absolute;
  }
  td div {
    margin-top:20px;
  }
  select {
    border: none;
    color: #999;
    appearance: none;
  }
  .layui-icon {
    font-size: 32px;
  }
  .tagspan{
    margin-top:11px;
    border:2px solid darkred;
  }
</style>
<body onselectstart="return false">
<div class="container">
  <div class="custom-menu-edit-con" style="margin-right:3%;">
    <div class="hbox" style="border:1px solid #dee5e7;position:absolute;">
      <table class="layui-table" style="margin:0">
        <colgroup>
          <col width="20%">
          <col width="20%">
          <col width="20%">
          <col width="20%">
          <col width="20%">
        </colgroup>
        <tr>
          <th type="mpnews"><i class="layui-icon layui-icon-face-smile">&#xe63c;</i><span>图文消息</span></th>
          <th type="text"><i class="layui-icon layui-icon-face-smile">&#xe60a;</i><span>文字</span></th>
          <th type="image"><i class="layui-icon layui-icon-face-smile">&#xe60d;</i><span>图片</span></th>
          <th type="voice"><i class="layui-icon layui-icon-face-smile">&#xe688;</i><span>语音</span></th>
          <th type="video"><i class="layui-icon layui-icon-face-smile">&#xe6ed;</i> <span>视频</span></th>
        </tr>
        <tr>
          <td colspan="5" id="td">
            <form id="uploadForm" enctype="multipart/form-data">
            <p id="p" style="position: absolute;top:50%;left:40%;font-size:50px;font-family:楷体">请选择类型</p>
            <div class="mpnews video" style="display:none;">
                <div class="modern-forms">
                  <div class="field-group">
                    <input type="text" class="mdn-input title" name="title" placeholder="请输入标题">
                    <label class="mdn-label">标题</label>
                    <span class="mdn-bar"></span>
                  </div>
                  <div class="field-group">
                    <textarea placeholder="请输入描述" name="content" style="border:1px solid #f8f8ff;margin-top: 5px" class="layui-textarea mdn-input content"></textarea>
                    <label class="mdn-label">描述</label>
                    <span class="mdn-bar"></span>
                  </div>
                  <label class="field-group mdn-upload">
                    <input type="file" class="mdn-file file" name="file" accept=".mp4,.png,.jpg" onchange="document.getElementById(&#39;filenv&#39;).value = this.value;">
                    <input class="mdn-input" id="filenv" placeholder="请上传缩略图" readonly="">
                    <label class="mdn-label">文件上传器</label>
                    <span class="mdn-bar"></span>
                    <span class="mdn-button btn-primary">选择文件</span>
                  </label>
                  <button type="button" style="float:right" class="mdn-button btn-flat btn">发送</button>
                  <span class="tagspan" style="float:right">
                    &ensp;群发对象
                    <select class="tag">
                      <option value="0">全部用户</option>
                      @foreach($tagInfo as $v)
                      <option value="{{$v['id']}}">{{$v['name']}}</option>
                      @endforeach
                    </select>
                  </span>
                </div>
            </div>
            <div class="text" style="display:none;">
              <div class="modern-forms">
                <div class="field-group">
                  <textarea placeholder="请输入描述" name="content" style="border:1px solid #f8f8ff;margin-top: 5px" class="layui-textarea mdn-input content"></textarea>
                  <label class="mdn-label">描述</label>
                  <span class="mdn-bar"></span>
                </div>
                <button type="button" style="float:right" class="mdn-button btn-flat btn">发送</button>
                <span class="tagspan" style="float:right">
                  &ensp;群发对象
                  <select class="tag">
                    <option value="0">全部用户</option>
                    @foreach($tagInfo as $v)
                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                    @endforeach
                  </select>
                </span>
              </div>
            </div>
            <div class="image voice" style="display:none;">
              <div class="modern-forms">
                <label class="field-group mdn-upload">
                  <input type="file" class="mdn-file file" name="file" accept=".amr,.png,.jpg" onchange="document.getElementById(&#39;fileiv&#39;).value = this.value;">
                  <input class="mdn-input" id="fileiv" placeholder="请上传缩略图" readonly="">
                  <label class="mdn-label">文件上传器</label>
                  <span class="mdn-bar"></span>
                  <span class="mdn-button btn-primary">选择文件</span>
                </label>
                <button type="button" style="float:right" class="mdn-button btn-flat btn">发送</button>
                <span class="tagspan" style="float:right">
                  &ensp;群发对象
                  <select class="tag">
                    <option value="0">全部用户</option>
                    @foreach($tagInfo as $v)
                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                    @endforeach
                  </select>
                </span>
              </div>
            </div>
          </form>
          </td>
        </tr>
      </table>
      <div style="bottom:0;position:absolute;width:100%;">
      </div>
    </div>
  </div>
</div>
<script>
  layui.use(['form'], function() {
    var form = layui.form;
    //切换类型
    $('th').click(function() {
      var type = $(this).attr('type');
      var _this = $("."+type);
      _this.css('display','block');
      $(this).css('color','tomato');
      $(this).siblings('th').css('color','');
      $('#p').css('display','none');
      _this.siblings('div').css('display','none');
    })

    //上传文件
    $('.file').change(function(){
      var formData = new FormData($('#uploadForm')[0]);
      layer.msg('上传中请稍后',{time:1500},function(){
        $.ajax({
          type:'post',
          url:'/admin/uploadfile',
          data:formData,
          cache: false,
          processData: false,
          contentType: false,
        })
      })
    })

    //提交
    $('.btn').click(function(){
      var _this = $(this);
      var ele = _this.parents("div[style='display: block;']").children().children();
      var type = _this.parents("div[style='display: block;']").prop('class');
      var tag = ele.children("select").val();
      if(type.search('mpnews') != -1){ //图文、视频
        var title = ele.children("input[type='text']").val();
        var content = ele.children("textarea").val();
      }else if(type.search('text') != -1){ //文本
        var content = ele.children("textarea").val();
      }
      $.ajax({
        type:'post',
        url:'/admin/groupsent',
        data:{_token:'{{csrf_token()}}',title:title,content:content,type:type,tag:tag},
      }).done(function(msg){
        layer.msg(msg);
      })
    })
  })
</script>