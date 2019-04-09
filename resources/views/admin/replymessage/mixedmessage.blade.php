<!DOCTYPE html>
<html lang="zh-CN" class="translated-ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>添加信息</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{url('css/Modern/modernforms.css')}}">
  <link rel="stylesheet" type="text/css" href="{{url('css/Modern/font-awesome.4.6.0.css')}}">
  <link rel="stylesheet" href="{{url('css/Modern/theme-red.css')}}">
  <link type="text/css" rel="stylesheet" charset="UTF-8" href="{{url('css/Modern/translateelement.css')}}">
  <link rel="stylesheet" href="{{url('/Ladmin/font.css')}}">
</head>
<body class="mdn-bg">
  <div class="modern-forms">
    <div class="modern-container">
      <form action="{{url('/admin/upmessage')}}" method="post" enctype="multipart/form-data">
        @csrf
        <fieldset>
          <div class="mdn-group">
            <label class="field-group mdn-upload">
              <input type="hidden" name="messageType" value="{{$messageType}}">
              <input type="file" class="mdn-file" name="file" id="file"
                onchange="document.getElementById(&#39;fileinput&#39;).value = this.value;">
              <input type="text" class="mdn-input" id="fileinput" placeholder="没有选择文件" readonly="">
              <label class="mdn-label">
                <font style="vertical-align: inherit;">
                  <font style="vertical-align: inherit;">
                  文件上传器
                  @if($messageType == 'image')
                  <i class="iconfont">&#xf0044;</i>
                  @elseif($messageType == 'voice')
                  <i class="iconfont">&#xf0147;</i>
                  @elseif($messageType == 'video')
                  <i class="iconfont">&#xf0162;</i>
                  @elseif($messageType == 'music')
                  <i class="iconfont">&#xf0064;</i>
                  @endif
                  </font>
                </font>
              </label>
              <span class="mdn-bar"></span>
              <span class="mdn-button btn-primary">
                <font style="vertical-align: inherit;">
                  <font style="vertical-align: inherit;"> 选择文件 </font>
                </font>
              </span>
            </label>
          </div>
        </fieldset>
        <div class="mdn-footer">
          <input type="submit" style="float:right" class="mdn-button btn-flat" value="提交">
        </div>
      </form>
    </div>
  </div>
</body>
</html>