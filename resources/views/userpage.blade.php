@extends('master')

@section('title','我的雪天')
<link href="{{url('css/member.css')}}" rel="stylesheet" type="text/css" />
<script src="js/jquery190_1.js" language="javascript" type="text/javascript"></script>
@section('content')
<body class="g-acc-bg">
    @if($checkLogin == 2)
    <div class="welcome">
        <p>Hi，等你好久了！</p>
        <a href="{{url('/login')}}" class="orange">登录</a>
        <a href="{{url('/register')}}" class="orange">注册</a>
    </div>
    @else
    <div class="welcome">
        <a href="{{url('set')}}"><i class="set"></i></a>
        <div class="login-img clearfix">
            <ul>
                <!-- <li><img src="/uploads/Tou.jpg" alt=""></li> -->
                <li><img class="lazy" src='{{url("/uploads/hh.gif")}}'></li>
                <li class="name">
                    <h3>{{$userInfo['user_name']}}</h3>
                    <p>ID：{{sprintf("%06d",$userInfo['user_id'])}}</p>
                </li>
                <li class="next fr"><a href="{{url('edituser')}}"><s></s></a></li>
            </ul>
        </div>
    </div>

    <!--获得的商品-->
    
    <!--导航菜单-->
    
    <div class="sub_nav marginB person-page-menu">
        <a href="{{url('buyrecord')}}"><s class="m_s1"></s>购物记录<i></i></a>
        <a href="/v44/member/orderlist.do"><s class="m_s2"></s>获得的商品<i></i></a>
        <a href="/v44/member/postlist.do"><s class="m_s3"></s>我的晒单<i></i></a>
        <a href="{{url('mywallet')}}"><s class="m_s4"></s>我的钱包<i></i></a>
        <a href="{{url('address')}}"><s class="m_s5"></s>收货地址<i></i></a>
        <a href="/v44/help/help.do" class="mt10"><s class="m_s6"></s>帮助与反馈<i></i></a>
        <a href="{{url('invite')}}"><s class="m_s7"></s>二维码分享<i></i></a>
        <p class="colorbbb">客服热线：400-666-2110  (工作时间9:00-17:00)</p>
    </div>
    @endif
    <div class="footer clearfix">
        <ul>
            <li class="f_home"><a href="{{url('/')}}" ><i></i>首页</a></li>
            <li class="f_announced"><a href="{{url('allshops')}}"><i></i>全部商品</a></li>
            <li class="f_car"><a id="btnCart" href="{{url('shopcart')}}" ><i></i>购物车</a></li>
            <li class="f_personal"><a href="{{url('userpage')}}" class="hover"><i></i>我的信息</a></li>
        </ul>
    </div>
</body>
@endsection

@section('my-js')
<script>
    function goClick(obj, href) {
        $(obj).empty();
        location.href = href;
    }
    if (navigator.userAgent.toLowerCase().match(/MicroMessenger/i) != "micromessenger") {
        $(".m-block-header").show();
    }
</script>
@endsection