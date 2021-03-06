<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>安全设置</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/safeset.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
    
</head>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">安全设置</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="{{url('/')}}" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li>
                    <em class="login"></em>
                    <a href="{{url('/set/safeset/loginpwd')}}">登录密码</a>
                    <s class="fr loginpwd"></s>
                    <span class="fr loginpwd">修改</span>
                </li>
                <li>
                    <em class="pay"></em>
                    <a href="javascript:void(0);">支付密码</a>
                    <s class="fr"></s>
                    <span class="fr">已开启</span>
                </li>
                <li>
                    <em class="card"></em>
                    <a href="javascript:void(0);">银行卡</a>
                    <s class="fr"></s>
                </li>           
            </ul>     
        </div>
    </div>
</body>
</html>
<script>
    $(function(){
        $('.loginpwd').click(function(){
            location.href="{{url('/set/safeset/loginpwd')}}";
        })
    })
</script>