@extends('master')

@section('title','编辑个人资料')
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
@section('content')
<body>
    
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">编辑个人资料</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="{{url('/')}}" class="m-index-icon"><i class="m-public-icon"></i></a>
    </div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <!-- <li class="headimg">
                    <a href="">头像</a>
                    <s class="fr"></s>
                    <span class="img fr"></span>
                </li> -->
                <li>
                    <a href="">昵称</a>
                    <s class="fr"></s>
                    <span class="fr name">{{$userInfo->user_name}}</span>
                </li>
                <!-- <li>
                    <a href="">我的主页</a>
                    <s class="fr"></s>
                </li> -->
                <li>
                    <a href="">手机号码</a>
                    <span class="fr">{{$userInfo->user_tel}}</span>
                </li>           
            </ul>     
        </div>
        <div class="quit">
            <a href="{{url('/logout')}}">退出登录</a>
        </div>
    </div>
</body>
@endsection
<script>
    $(function(){
        $('.name').click(function(){
            location.href="{{url('/edituser/namemodify')}}"
        })
    })
</script>