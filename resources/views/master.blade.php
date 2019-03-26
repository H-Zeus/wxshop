<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title')</title>
    <meta content="app-id=518966501" name="apple-itunes-app" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" name="viewport" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link href="{{url('css/index.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('layui/css/layui.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{url('layui/layui.js')}}"></script>
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    
    
</head>

@yield('content')

<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script src="{{url('js/all.js')}}"></script>
<script src="{{url('js/index.js')}}"></script>
<script src="{{url('js/lazyload.min.js')}}"></script>
<script src="{{url('js/jquery-1.8.3.min.js')}}"></script>
<script src="http://cdn.bootcss.com/flexslider/2.6.2/jquery.flexslider.min.js"></script>

@yield('my-js')
</html>