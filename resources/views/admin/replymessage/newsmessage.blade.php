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
</head>
<body class="mdn-bg">
  <div class="modern-forms">
    <div class="modern-container">
      <form action="{{url('/admin/upmessage')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="messageType" value="news">
        <fieldset>
          <div class="mdn-group">
            <div class="col col-6">
              <div class="field-group">
                <input type="text" class="mdn-input" name="m_title" placeholder="请输入标题">
                <label class="mdn-label">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">标题</font>
                  </font>
                </label>
                <span class="mdn-bar"></span>
              </div>
            </div>
          </div>
          <div class="mdn-group">
            <div class="col col-6">
              <div class="field-group">
                <input type="text" class="mdn-input" name="m_content" placeholder="请输入描述">
                <label class="mdn-label">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">描述</font>
                  </font>
                </label>
                <span class="mdn-bar"></span>
              </div>
            </div>
          </div>
          <div class="mdn-group">
            <div class="col col-6">
              <div class="field-group">
                <input type="url" class="mdn-input" name="m_url" placeholder="请输入链接">
                <label class="mdn-label">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">链接</font>
                  </font>
                </label>
                <span class="mdn-bar"></span>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <div class="mdn-group">
            <label class="field-group mdn-upload">
              <input type="file" class="mdn-file" name="file" id="file"
                onchange="document.getElementById(&#39;fileinput&#39;).value = this.value;">
              <input type="text" class="mdn-input" id="fileinput" placeholder="没有选择文件" readonly="">
              <label class="mdn-label">
                <font style="vertical-align: inherit;">
                  <font style="vertical-align: inherit;">文件上传器</font>
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