@extends('master')

@section('title','设置')
<meta content="app-id=984819816" name="apple-itunes-app" />
<link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
<!-- <script src="{{url('js/jquery-1.11.2.min.js')}}"></script> -->
@section('content')
<body>
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">设置</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="{{url('/')}}" class="m-index-icon"><i class="m-public-icon"></i></a>
    </div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li>
                    <a href="">编辑个人资料</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">邀请有奖</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">安全设置</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">客服热线（9:00-17:00）</a>
                    <s class="fr"></s>
                    <span class="fr">400-666-2110</span>
                </li>           
            </ul>     
        </div>
        <div class="quit">
            <a href="{{url('set/quit')}}">退出登录</a>
        </div>
    </div>
</body>
@endsection