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
        <input type="hidden" name="messageType" value="text">
        <fieldset>
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
        </fieldset>
        <div class="mdn-footer">
          <input type="submit" style="float:right" class="mdn-button btn-flat" value="提交">
        </div>
      </form>
    </div>
  </div>
</body>
</html>