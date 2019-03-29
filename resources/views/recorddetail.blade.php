<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>我的账单</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{url('css/buyrecord.css')}}">    
</head>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">我的账单</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="{{url('/')}}" class="m-index-icon"><i class="buycart"></i></a>
</div>
@foreach($info as $v)
<div class="buyrecord-con clearfix">
    <div class="record-img fl">
        <img src='{{url("uploads/goodsimg/$v->goods_img")}}' alt="">
    </div>
    <div class="record-con fl">
        <h3>{{$v->goods_name}}*{{$v->buy_number}}</h3>
        <p class="winner">&ensp;</p>
        <span class="winner" style="font-size:16px;line-height:16px;color:red">￥{{$v->buy_number*$v->self_price}}</span>
        <span class="w-chao" style="float:right;margin-right:10px">{{$v->order_status}}</span>
        <div class="clearfix">
            <div class="win-wrapp fl">
                <p class="winner">&ensp;</p>
                <p class="w-time">{{date('Y-m-d H:i:s',$v->create_time)}}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
<script src="js/jquery-1.11.2.min.js"></script>




</body>
</html>
