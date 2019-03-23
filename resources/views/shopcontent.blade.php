@extends('master')

@section('title','商品详情')
<style>
    .pro_foot i {background-position:0 -47px;background:url(../images/set.png);background-size:35px auto;}
    .Countdown-con {padding: 4px 15px 0px;}
</style>
<meta content="app-id=984819816" name="apple-itunes-app" />
<link href="{{url('css/fsgallery.css')}}" rel="stylesheet" charset="utf-8">
<link href="{{url('css/goods.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{url('css/swiper.min.css')}}">
@section('content')
<body fnav="2" class="g-acc-bg">
    <div class="page-group">
        <div id="page-photo-browser" class="page">
            <!--触屏版内页头部-->
        <div class="m-block-header" id="div-header">
            <strong id="m-title">商品详情</strong>
            <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
            <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
        </div>

                <!-- 焦点图 -->
                <div class="hotimg-wrapper">
                    <div class="hotimg-top"></div>
                    <section id="gallery" class="hotimg">
                        <ul class="slides" style="width: 600%; transition-duration: 0.4s; transform: translate3d(-828px, 0px, 0px);">
                            <li style="width: 414px; float: left; display: block;" class="clone">
                                <a href="javascript:void(0);">
                                    <img src='{{url("/uploads/goodsimg/$goodsInfo->goods_img")}}' style="height:80%" alt="">
                                </a>
                            </li>

                            @foreach($goods_imgs as $v)
                            <li style="width: 414px; float: left; display: block;" class="flex-active-slide">
                                <a href="javascript:void(0);"><img src='{{url("/uploads/goodsimg/$v")}}' style="height:80%" alt="">
                                </a>
                            </li>
                            @endforeach

                        </ul>
                    </section>
                </div>
                <!-- 产品信息 -->
                <div class="pro_info">
                    <h2 class="gray6">
                        <span>{{$goodsInfo->goods_name}}</span>
                    </h2>
                    <div class="purchase-txt gray9 clearfix">
                        价值：￥{{$goodsInfo->self_price}}
                    </div>
                    <div class="clearfix">
                        
                        <div class="gRate">
                            <div class="Progress-bar">
                                <p class="u-progress" title="已完成90%">
                                    <span class="pgbar" style="width:{{$goodsInfo->goods_num/10}}%;">
                                        <span class="pging"></span>
                                    </span>
                                </p>
                                <ul class="Pro-bar-li">
                                    <li class="P-bar01"><em>{{1000-$goodsInfo->goods_num}}</em>已参与</li>
                                    <li class="P-bar02"><em>1000</em>总需人次</li>
                                    <li class="P-bar03"><em>{{$goodsInfo->goods_num}}</em>剩余</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--揭晓倒计时-->
                <div style="width:100%">
                {!!$goodsInfo->goods_desc!!}
                </div>
                <div class="pro_foot"> 
                    <a href="{{url('/shopcart/ordersupplyment')}}/{{$goodsInfo->goods_id}}" class="shopping" style="width:70%">立即购买</a>
                    <span href="" class="fr"><i><b num="1">1</b></i></span>         
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

<script src="{{url('js/swiper.min.js')}}"></script>
<!-- <script src="{{url('js/photo.js')}}" charset="utf-8"></script> -->

@section('my-js')
<script>
    $(function () {  
        $('.hotimg').flexslider({   
            directionNav: false,   //是否显示左右控制按钮   
            controlNav: true,   //是否显示底部切换按钮   
            pauseOnAction: false,  //手动切换后是否继续自动轮播,继续(false),停止(true),默认true   
            animation: 'slide',   //淡入淡出(fade)或滑动(slide),默认fade
            slideshowSpeed: 3000,  //自动轮播间隔时间(毫秒),默认5000ms
            animationSpeed: 150,   //轮播效果切换时间,默认600ms   
            direction: 'horizontal',  //设置滑动方向:左右horizontal或者上下vertical,需设置animation: "slide",默认horizontal   
            randomize: false,   //是否随机幻切换   
            animationLoop: true   //是否循环滚动  
        });  
        setTimeout($('.flexslider img').fadeIn()); 


        // 滑动
        var tabsSwiper = new Swiper('#tabs-container',{
            speed:500,
            onSlideChangeStart: function(){
              $(".tabs .active").removeClass('active')
              $(".tabs a").eq(tabsSwiper.activeIndex).addClass('active')  
            }
        })
        $(".tabs a").on('touchstart mousedown',function(e){
            e.preventDefault()
            $(".tabs .active").removeClass('active')
            $(this).addClass('active')
            tabsSwiper.slideTo( $(this).index() )
        })
        $(".tabs a").click(function(e){
            e.preventDefault()
        })
    }) 
</script>
@endsection