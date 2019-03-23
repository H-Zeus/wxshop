@extends('master')

@section('title','编辑个人资料')
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />

@section('content')
<body>
    
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">编辑个人资料</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
    </div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li class="headimg">
                    <a href="">头像</a>
                    <s class="fr"></s>
                    <span class="img fr"></span>
                </li>
                <li>
                    <a href="">昵称</a>
                    <s class="fr"></s>
                    <span class="fr">雪</span>
                </li>
                <li>
                    <a href="">我的主页</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">手机号码</a>
                    <span class="fr">400-666-2110</span>
                </li>           
            </ul>     
        </div>
        <div class="quit">
            <a href="">退出登录</a>
        </div>
    </div>

    <div class="footer clearfix" style="display:none;">
        <ul>
            <li class="f_home"><a href="/v45/index.do" ><i></i>潮购</a></li>
            <li class="f_announced"><a href="/v45/lottery/" ><i></i>最新揭晓</a></li>
            <li class="f_single"><a href="/v45/post/index.do" ><i></i>晒单</a></li>
            <li class="f_car"><a id="btnCart" href="/v45/mycart/index.do" ><i></i>购物车</a></li>
            <li class="f_personal"><a href="/v45/member/index.do" ><i></i>我的潮购</a></li>
        </ul>
    </div>
</body>
@endsection