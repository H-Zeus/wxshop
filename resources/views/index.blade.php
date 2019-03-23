@extends('master')

@section('title','雪天')

@section('content')
<body fnav="1" class="g-acc-bg">
	<div class="marginB" id="loadingPicBlock">
	<!--首页头部-->
	<div class="m-block-header" style="display: none">
		<div class="search"></div>
		<a href="/" class="m-public-icon m-1yyg-icon"></a>
	</div>
	<!--首页头部 end-->

	<!-- 关注微信 -->
	<div id="div_subscribe" class="app-icon-wrapper" style="display: none;">
		<div class="app-icon">
			<a href="javascript:;" class="close-icon"><i class="set-icon"></i></a>
			<a href="javascript:;" class="info-icon">
				<i class="set-icon"></i>
				<div class="info">
					<p>点击关注666潮人购官方微信^_^</p>
				</div>
			</a>
		</div>
	</div>

	<!-- 焦点图 -->
	<div class="hotimg-wrapper">
		<div class="hotimg-top"></div>
		<section id="sliderBox" class="hotimg">
			<ul class="slides" style="width: 600%; transition-duration: 0.4s; transform: translate3d(-828px, 0px, 0px);">
				<li style="width: 414px; float: left; display: block;" class="clone">
					<a href="javascript:void(0);">
						<img src="https://img20.360buyimg.com/da/jfs/t1/27202/9/9209/296326/5c7c906fEd4d02714/0be5d8223ae9c0f1.jpg" alt="">
					</a>
				</li>
				<li class="" style="width: 414px; float: left; display: block;">
					<a href="javascript:void(0);">
						<img src="https://img20.360buyimg.com/da/jfs/t1/9987/40/10930/483460/5c25fc52Eb74fabdb/07b3f9394d9633c4.jpg" alt="">
					</a>
				</li>
				<li style="width: 414px; float: left; display: block;" class="flex-active-slide">
					<a href="javascript:void(0);">
						<img src="https://img14.360buyimg.com/da/jfs/t1/31415/22/3653/203144/5c779edeE8cb5c255/3523458c866f31de.jpg" alt="">
					</a>
				</li>
				<li style="width: 414px; float: left; display: block;" class="">
					<a href="javascript:void(0);">
						<img src="https://img12.360buyimg.com/da/jfs/t1/31413/7/4354/576747/5c7cba2fE87c9c3fc/9567f38d54528979.jpg" alt="">
					</a>
				</li>
				<li style="width: 414px; float: left; display: block;" class="">
					<a href="javascript:void(0);">
						<img src="https://img20.360buyimg.com/da/jfs/t1/9987/40/10930/483460/5c25fc52Eb74fabdb/07b3f9394d9633c4.jpg" alt="">
					</a>
				</li>
				<li class="clone" style="width: 414px; float: left; display: block;">
					<a href="javascript:void(0);">
						<img src="https://img14.360buyimg.com/da/jfs/t1/31415/22/3653/203144/5c779edeE8cb5c255/3523458c866f31de.jpg" alt="">
					</a>
				</li>
			</ul>
		</section>
	</div>
	<!--分类-->
	<div class="index-menu thin-bor-top thin-bor-bottom">
		<ul class="menu-list">
		@foreach($categoryInfo as $v)
			<li>
				<a href="/allshops/{{$v->cate_id}}" id="btnNew">
					<i class="xinpin"></i>
					<span class="title">{{$v->cate_name}}</span>
				</a>
			</li>
		@endforeach
		</ul>
	</div>
	<!--导航-->
	<div class="success-tip">
		<div class="left-icon"></div>
		<ul class="right-con">
			<li>
				<span style="color: #4E555E;">
					<a href="./index.php?i=107&amp;c=entry&amp;id=10&amp;do=notice&amp;m=weliam_indiana" style="color: #4E555E;">恭喜<span class="username">H</span>获得了<span>iphonex 黑色 256G 闪耀你的眼</span></a>
				</span>
			</li>
			<li>
				<span style="color: #4E555E;">
					<a href="./index.php?i=107&amp;c=entry&amp;id=10&amp;do=notice&amp;m=weliam_indiana" style="color: #4E555E;">恭喜<span class="username">A</span>获得了<span>iphoneSE 白色 128G 闪耀你的眼</span></a>
				</span>
			</li>
			<li>
				<span style="color: #4E555E;">
					<a href="./index.php?i=107&amp;c=entry&amp;id=10&amp;do=notice&amp;m=weliam_indiana" style="color: #4E555E;">恭喜<span class="username">N</span>获得了<span>iphone8P 黑色 128G 闪耀你的眼</span></a>
				</span>
			</li>
		</ul>
	</div>

	<!-- 商品列表 -->
	<div class="line guess">
		<div class="hot-content">
			<i></i>
			<span>商品列表</span>
			<div class="l-left"></div>
			<div class="l-right"></div>
		</div>
	</div>
	<!-- 商品列表-->
	<div class="goods-wrap marginB">
		<ul id="ulGoodsList" class="goods-list clearfix">
			<input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
			@foreach($goodsInfo as $v)
			<li id="23558" codeid="12751965" goodsid="23558" codeperiod="28436">
				<a href="{{url('shopcontent')}}/{{$v->goods_id}}" class="g-pic">
					<img class="lazy" name="goodsImg" data-original='{{url("/uploads/hh.gif")}}'src='{{url("/uploads/hh.gif")}}' width="136" height="136">
					<!-- <img class="lazy" name="goodsImg" src='{{url("/uploads/goodsimg/$v->goods_img")}}' width="136" height="136"> -->
				</a>
				<p class="g-name"><a href="{{url('shopcontent')}}/{{$v->goods_id}}">{{$v->goods_name}}</a></p>
				<ins class="gray9">价值：￥{{$v->self_price}}</ins>
				<div class="Progress-bar">
					<p class="u-progress">
						<span class="pgbar" style="width:{{$v->goods_num/10}}%;">
							<span class="pging"></span>
						</span>
					</p>

				</div>
				<div class="btn-wrap" name="buyBox" limitbuy="0" surplus="58" totalnum="1625" alreadybuy="1567">
					<a href="{{url('/shopcart/ordersupplyment')}}/{{$v->goods_id}}" class="buy-btn" codeid="12751965">立即购买</a>
					<div class="gRate" codeid="12751965" canbuy="58">
						<a href="javascript:;" class="cartadd" goods_id="{{$v->goods_id}}"></a>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
		<div class="loading clearfix"><b></b>正在加载</div>
	</div>  
	<!--底部-->
	<div class="footer clearfix">
			<ul>
					<li class="f_home"><a href="{{url('/')}}" class="hover"><i></i>首页</a></li>
					<li class="f_announced"><a href="{{url('allshops')}}"><i></i>全部商品</a></li>
					<li class="f_car"><a id="btnCart" href="{{url('shopcart')}}" ><i></i>购物车</a></li>
					<li class="f_personal"><a href="{{url('userpage')}}" ><i></i>我的信息</a></li>
			</ul>
	</div>
	<div id="div_fastnav" class="fast-nav-wrapper">
		<ul class="fast-nav">
			<li id="li_menu" isshow="0">
				<a href="javascript:;"><i class="nav-menu"></i></a>
			</li>
			<li id="li_top" style="display: none;">
				<a href="javascript:;"><i class="nav-top"></i></a>
			</li>
		</ul>
		<div class="sub-nav four" style="display: none;">
			<a href="#"><i class="announced"></i>最新揭晓</a>
			<a href="#"><i class="single"></i>晒单</a>
			<a href="#"><i class="personal"></i>我的潮购</a>
			<a href="#"><i class="shopcar"></i>购物车</a>
		</div>
	</div>
</body>
@endsection


@section('my-js')
<script>
	$(function(){
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
		// setTimeout($('.flexslider img').fadeIn());
		// jQuery(document).ready(function() {
		// 	$("img.lazy").lazyload({
		// 		placeholder : "images/loading2.gif",
		// 		effect: "fadeIn",
		// 	});
		// });

		// 返回顶部点击事件
		$('#div_fastnav #li_menu').click(function(){
				if($('.sub-nav').css('display')=='none'){
					$('.sub-nav').css('display','block');
				}else{
					$('.sub-nav').css('display','none');
				};
		});
		$("#li_top").click(function(){
			$('html,body').animate({scrollTop:0},300);
			return false; 
		}); 

		$(window).scroll(function(){
			if($(window).scrollTop()>200){
				$('#li_top').css('display','block');
			}else{
				$('#li_top').css('display','none');
			};
		});
	});
</script>
<script>
    $(function(){
        //加入购物车
        $(document).on('click','.cartadd',function(){
            var _this = $(this);
            var goods_id = _this.attr('goods_id');
            var _token = $('#_token').val();
            $.ajax({
                url:'cartadd',
                type:'post',
                data:{goods_id:goods_id,_token:_token}
            }).done(function(res){
                // console.log(res);
            })
        })
    })
</script>
@endsection