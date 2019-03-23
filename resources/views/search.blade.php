@extends('master')
@section('title','搜索')
<link href="{{url('css/goods.css')}}" rel="stylesheet" type="text/css" />
@section('content')

<body class="g-acc-bg m-site-box" fnav="2">
    <input name="hidSearchKey" type="hidden" id="hidSearchKey" value="黄金" />
    <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
    <!--触屏版内页头部-->
    <div class="pro-s-box thin-bor-bottom search-box pos-fix-0" id="divSearch">
        <div class="box">
            <div class="border">
                <div class="border-inner"></div>
            </div>
            <div class="input-box">
                <i class="s-icon"></i>
                <input type="text" placeholder="输入“汽车”试试" value="" id="txtSearchs" maxlength="10" />
                <i class="c-icon" id="btnClearInput" style="display: none"></i>
            </div>
        </div>
        <a href="javascript:;" class="s-btn" id="btnSearch">搜索</a>
    </div>
    <!--搜索时显示的模块-->
    <div class="search-info is_history" style="display: none">
        <!-- <div class="hot">
            <p class="title">热门搜索</p>
            <ul id="ulSearchHot" class="hot-list clearfix">
                <li wd='iPhone'><a class="items">iPhone</a></li>
                <li wd='三星'><a class="items">三星</a></li>
                <li wd='小米'><a class="items">小米</a></li>
                <li wd='黄金'><a class="items">黄金</a></li>
                <li wd='汽车'><a class="items">汽车</a></li>
                <li wd='电脑'><a class="items">电脑</a></li>
            </ul>
        </div> -->
        <div class="history">
            <p class="title">历史记录</p>
            <div class="his-inner" id="divSearchHotHistory">
                <ul class="his-list thin-bor-top">
                    <li wd="小米移动电源" class="thin-bor-bottom"><a class="items">小米移动电源</a></li>
                    <li wd="苹果6" class="thin-bor-bottom"><a class="items">苹果6</a></li>
                    <li wd="苹果电脑" class="thin-bor-bottom"><a class="items">苹果电脑</a></li>
                </ul>
                <div class="cle-cord thin-bor-bottom" id="btnClear">清空历史记录</div>
            </div>
        </div>
    </div>

    <!--搜索结果模块-->
    <div class="good-result pad-top-86 is_goodsInfo" id="loadingPicBlock" style="display: block;">
        @if($goodsInfo != [])
        <!--搜索有结果时-->
        <div class="goodList">
            <div class="result-num thin-bor-bottom pos-fix-44" id="divResultTip">
                <p style="text-align:center">
                    共搜索到&nbsp;
                    <span class="orange" id="spCount">{{$goodsInfoNum}}</span>
                    &nbsp;个相关商品
                </p>
                <!-- <div class="add-car-all" id="multipleAddToCartBtn">一键加入购物车</div> -->
            </div>
            <ul id="ulGoodsList">
                @foreach($goodsInfo as $v)
                <li id="23901">
                    <span class="gList_l fl">
                        <!-- <img src="{{url('/uploads/hh.gif')}}"> -->
                        <img src='{{url("/uploads/goodsimg/$v->goods_img")}}'>
                    </span>
                    <div class="gList_r">
                        <h3 class="gray6">{{$v->goods_name}}</h3>
                        <em class="gray9">价值：￥{{$v->self_price}}</em>
                        <div class="gRate">
                            <div class="Progress-bar">
                                <p class="u-progress">
                                    <span style="width: {{$v->goods_num/10}}%;" class="pgbar">
                                        <span class="pging"></span>
                                    </span>
                                </p>
                                <ul class="Pro-bar-li">
                                    <li class="P-bar01"><em>{{1000-$v->goods_num}}</em>已参与</li>
                                    <li class="P-bar02"><em>1000</em>总需人次</li>
                                    <li class="P-bar03"><em>{{$v->goods_num}}</em>剩余</li>
                                </ul>
                            </div>
                            <a codeid="13470136" class="cartadd" goods_id="{{$v->goods_id}}"><s></s></a>
                        </div>
                    </div>
                </li>
                @endforeach
                <li id="23901"></li>
                <li id="23901"></li>
            </ul>
        </div>
        @else
        <!--搜索无结果时-->
        <div class="null-search-wrapper" id="divNoneData">
            <div class="null-search-inner">
                <i class="null-search-icon"></i>
                <p class="gray9">抱歉，没有您想要的商品！</p>
            </div>
        </div>
        @endif
    </div>
    <!-- 底部 -->
    <div class="footer clearfix">
        <ul>
            <li class="f_home"><a href="{{url('/')}}"><i></i>首页</a></li>
            <li class="f_announced"><a href="{{url('allshops')}}" class="hover"><i></i>全部商品</a></li>
            <li class="f_car"><a id="btnCart" href="{{url('shopcart')}}"><i></i>购物车</a></li>
            <li class="f_personal"><a href="{{url('userpage')}}"><i></i>我的信息</a></li>
        </ul>
    </div>
</body>
@endsection
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    //载入页面自动获取焦点
    window.onload = function() {
        var oInput = document.getElementById("txtSearchs");
        oInput.focus();
        $('#btnClearInput').css('display', 'block')
    }
    //输入框 获取焦点 (显示 ×)(显示历史记录 隐藏商品信息)
    $(document).on('focus', '#txtSearchs', function() {
        $('#btnClearInput').css('display', 'block');
        $('.is_history').show();
        $('.is_goodsInfo').hide();
    })
    //输入框 失去焦点 (隐藏 ×)(显示商品信息 隐藏历史记录)
    $(document).on('blur', '#txtSearchs', function() {
        $('#btnClearInput').css('display', 'none');
        $('.is_history').hide();
        $('.is_goodsInfo').show();
    })
    //点击 × 清空 输入内容
    $(document).on('click', '#btnClearInput', function() {
        $(this).prev().val('');
        $('#txtSearchs').focus();
    })
    //点击搜索 获取搜索内容
    $(document).on('click', '#btnSearch', function() {
        //获取当前页面url参数id
        var url = window.location.pathname;
        var checkUrl = url.slice(10)
        var _token = $('#_token').val();
        var keyword = $('#txtSearchs').val();
        if (checkUrl == '') {
            checkUrl = 0;
        }
        if (keyword == '') {
            layer.msg('别闹！！');
        } else {
            $.ajax({
                url: "/allshops/" + checkUrl + "/search",
                type: 'post',
                data: {
                    _token: _token,
                    keyword: keyword
                }
            }).done(function(res) {
                $('body').html(res);
            })
        }
    })
    //加入购物车
    $(document).on('click','.cartadd',function(){
        var _this = $(this);
        var _token = $('#_token').val();
        var goods_id = _this.attr('goods_id');
        $.ajax({
            url:'/cartadd',
            type:'post',
            data:{goods_id:goods_id,_token:_token}
        }).done(function(res){
            // console.log(res);
        })
    })

</script> 