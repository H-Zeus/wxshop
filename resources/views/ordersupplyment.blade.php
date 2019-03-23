@extends('master')

@section('title','订单详情')
    <meta content="app-id=984819816" name="apple-itunes-app">
    <link href="{{url('css/buyrecord.css')}}" rel="stylesheet" type="text/css">
    <script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
@section('content')
<body> 
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">订单详情</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"></a>
    </div>
    <div class="status">
        <img src="/images/wanshan.png" alt="">
    </div>
    <div class="userinfo">
        <div class="express-bottom">
            <ul class="clearfix">
                <li class="position"><s></s></li>
                <li class="info">
                    <div class="clearfix">
                        <span class="user fl">收货人：</span>
                        <span class="tel fr"></span>
                    </div>
                    <p class="noaddr">地址信息尚不完善，点击完善哦！</p>
                </li>
                <li><em></em></li>
            </ul>
        </div>
    </div>
    <div class="getshop">
        <div class="shopsimg fl">
            <img src='{{url("/uploads/goodsimg/$goodsInfo->goods_img")}}' alt="">
        </div>
        <div class="shopsinfo">
            <h3>{{$goodsInfo->goods_name}}</h3>
            <p class="price">价值：￥<i>{{$goodsInfo->self_price}}</i></p>
            <p>订单号：C17061673490875027850</p>
        </div>
        <div class="hot-line">
            <i></i><span>客服热线：400-666-2110</span>
        </div>

    </div>
    <div class="confirmaddr">
        <a href="">确认地址</a>
    </div>
</body>
@endsection