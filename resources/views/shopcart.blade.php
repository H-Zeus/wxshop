@extends('master')

@section('title','购物车')
<link href="{{url('css/cartlist.css')}}" rel="stylesheet" type="text/css" />

<body id="loadingPicBlock" class="g-acc-bg">
@section('content')
    <input name="hidUserID" type="hidden" id="hidUserID" value="-1" />
    <!--首页头部-->
    <div class="m-block-header">
        <strong id="m-title">购物车管理</strong>
        <a href="/" class="m-index-icon">编辑</a>
    </div>
    <!--首页头部 end-->
    <div class="g-Cart-list">
        @if($checkNum == 2)
        <ul id="cartBody">
            <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
            @foreach($cartInfo as $v)
            <li>
                <s class="xuan current"></s>
                <a class="fl u-Cart-img" href='{{url("/shopcontent/$v->goods_id")}}'>
                    <img src="/uploads/goodsimg/{{$v->goods_img}}" border="0" alt="">
                </a>
                <div class="u-Cart-r">
                    <a href='{{url("/shopcontent/$v->goods_id")}}' class="gray6">{{$v->goods_name}}</a>
                    <span class="gray9">
                        <em>剩余{{$v->goods_num}}件</em>
                    </span>
                    <div class="num-opt">
                        <em class="num-mius dis min"><i></i></em>
                        <input class="text_box key" cart_id="{{$v->cart_id}}" name="num" maxlength="6" type="text" value="{{$v->buy_number}}" price="{{$v->self_price}}" codeid="12501977">
                        <em class="num-add add"><i></i></em>
                    </div>
                    <a href="javascript:;" cart_id="{{$v->cart_id}}" name="delLink" cid="12501977" isover="0" class="z-del"><s></s></a>
                </div>    
            </li>
            @endforeach
        </ul>
        @else
        <div id="divNone" class="empty"><s></s><p>您的购物车还是空的哦~</p><a href="{{url('/')}}" class="orangeBtn">立即购物</a></div>
        @endif
    </div>
    <div id="mycartpay" class="g-Total-bt g-car-new" style="">
        <dl>
            <dt class="gray6">
                <s class="quanxuan current"></s>全选
                <p class="money-total">合计<em class="orange total"><span>￥</span></em></p>
            </dt>
            <dd>
                <a href="javascript:;" id="a_payment" class="orangeBtn w_account remove">删除</a>
                <a href="javascript:;" id="a_payment" class="orangeBtn w_account clearing">去结算</a>
            </dd>
        </dl>
    </div>
    <!-- <div class="hot-recom">
        <div class="title thin-bor-top gray6">
            <span><b class="z-set"></b>人气推荐</span>
            <em></em>
        </div>
        <div class="goods-wrap thin-bor-top">
            <ul class="goods-list clearfix">
                <li>
                    <a href="https://m.1yyg.com/v44/products/23458.do" class="g-pic">
                        <img src="https://img.1yyg.net/goodspic/pic-200-200/20160908092215288.jpg" width="136" height="136">
                    </a>
                    <p class="g-name">
                        <a href="https://m.1yyg.com/v44/products/23458.do">(第<i>368671</i>潮)苹果（Apple）iPhone 7 Plus 128G版 4G手机</a>
                    </p>
                    <ins class="gray9">价值:￥7130</ins>
                    <div class="btn-wrap">
                        <div class="Progress-bar">
                            <p class="u-progress">
                                <span class="pgbar" style="width:1%;">
                                    <span class="pging"></span>
                                </span>
                            </p>
                        </div>
                        <div class="gRate" data-productid="23458">
                            <a href="javascript:;"><s></s></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div> -->

    <div class="footer clearfix">
        <ul>
            <li class="f_home"><a href="{{url('/')}}"><i></i>首页</a></li>
            <li class="f_announced"><a href="{{url('allshops')}}"><i></i>全部商品</a></li>
            <li class="f_car"><a id="btnCart" href="{{url('shopcart')}}" class="hover"><i></i>购物车</a></li>
            <li class="f_personal"><a href="{{url('userpage')}}" ><i></i>我的信息</a></li>
        </ul>
	</div>
@endsection
</body>

<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<!---商品加减算总数---->
@section('my-js')
<script type="text/javascript">
    $(function () {
        var _token = $('#_token').val();
        $(".add").click(function () {
            var t = $(this).prev();
            var cart_id = $(this).prev().attr('cart_id');
            t.val(parseInt(t.val()) + 1);
            var buy_number = $(this).prev().val();
            GetCount();
            $.ajax({
                url:"/shopcart/add",
                type:'post',
                data:{_token:_token,cart_id:cart_id,buy_number:buy_number},
                async:false
            }).done(function(res){
                layer.msg(res);
                if(res != '修改成功'){
                    t.val(parseInt(t.val()) - 1);
                }
            })
        })
        $(".min").click(function () {
            var t = $(this).next();
            if(t.val()>1){
                var cart_id = $(this).next().attr('cart_id');
                t.val(parseInt(t.val()) - 1);
                var buy_number = $(this).next().val();
                GetCount();
                $.ajax({
                    url:"/shopcart/min",
                    type:'post',
                    data:{_token:_token,cart_id:cart_id,buy_number:buy_number},
                    async:false
                }).done(function(res){
                    layer.msg(res);
                    if(res != '修改成功'){
                        t.val(parseInt(t.val()) + 1);
                    }
                })
            }
        })
        var oldbuy_number;
        $('.key').focus(function(){
            oldbuy_number = $(this).val();
        })
        $(".key").blur(function () {
            var _this = $(this);
            var cart_id = _this.attr('cart_id');
            var buy_number = _this.val();
            $.ajax({
                url:"/shopcart/key",
                type:'post',
                data:{_token:_token,cart_id:cart_id,buy_number:buy_number},
                async:false
            }).done(function(res){
                layer.msg(res);
                if(res == '错误！购买数量不能为空' || res == '错误！购买数量必须大于0' || res == '错误！购买数量已超出库存'){
                    _this.val(oldbuy_number);
                }
            })
            GetCount();
        })
    })

    // 全选        
    $(".quanxuan").click(function () {
        if($(this).hasClass('current')){
            $(this).removeClass('current');

             $(".g-Cart-list .xuan").each(function () {
                if ($(this).hasClass("current")) {
                    $(this).removeClass("current"); 
                } else {
                    $(this).addClass("current");
                } 
            });
            GetCount();
        }else{
            $(this).addClass('current');

             $(".g-Cart-list .xuan").each(function () {
                $(this).addClass("current");
                // $(this).next().css({ "background-color": "#3366cc", "color": "#ffffff" });
            });
            GetCount();
        }
    });

    // 单选
    $(".g-Cart-list .xuan").click(function () {
        if($(this).hasClass('current')){
            $(this).removeClass('current');
        }else{
            $(this).addClass('current');
        }
        if($('.g-Cart-list .xuan.current').length==$('#cartBody li').length){
                $('.quanxuan').addClass('current');
            }else{
                $('.quanxuan').removeClass('current');
            }
        // $("#total2").html() = GetCount($(this));
        GetCount();
        //alert(conts);
    });

    var totalsum; //总金额
    // 已选中的总额
    function GetCount() {
        var conts = 0;
        $(".g-Cart-list .xuan").each(function () {
            if ($(this).hasClass("current")) {
                for (var i = 0; i < $(this).length; i++) {
                    conts += parseInt($(this).parents('li').find('input.text_box').val()*$(this).parents('li').find('input.text_box').attr('price'));
                }
            }
        });
        totalsum = conts;
         $(".total").html('<span>￥</span>'+(conts).toFixed(2));
    }
    GetCount();
</script>
<script>
    $(function(){
        var _token = $('#_token').val();
        layui.use(['layer'],function(){
            //行删
            $('.z-del').click(function(){
                var _this = $(this);
                var cart_id = _this.attr('cart_id');
                layer.confirm('您确认删除该商品吗?', {icon: 3, title:'确认删除'}, function(index){
                    // _this.parents('li').hide();
                    // _this.parent('div').siblings('s').attr('class','xuan');
                    $.ajax({
                        url:'/shopcart',
                        type:'post',
                        data:{_token:_token,cart_id:cart_id}
                    }).done(function(res){
                        // console.log(res);
                        layer.msg(res,{time:1200},function(){
                            history.go(0);
                        });
                    });
                    layer.close(index);
                });
            })

            //删除选中的
            var cart_id = '';
            $('.remove').click(function(){
                layer.confirm('您确认删除所选商品吗?', {icon: 3, title:'确认删除'}, function(index){
                    $(".g-Cart-list .xuan").each(function () {
                        if ($(this).hasClass("current")) {
                            cart_id += $(this).parent('li').find("a[class='z-del']").attr('cart_id')+',';
                        }
                    })
                    $.ajax({
                        url:'/shopcart/remove',
                        type:'post',
                        data:{_token:_token,cart_id:cart_id}
                    }).done(function(res){
                        layer.msg('删除成功',{time:1200},function(){
                            history.go(0);
                        });
                    })
                    layer.close(index);
                });
            });

            //结算
            $('.clearing').click(function(){
                //totalsum 总额
                layer.msg('别急，才 ￥'+totalsum+' 还不够呢快去再买点');
            })
        })
    })
</script>
@endsection