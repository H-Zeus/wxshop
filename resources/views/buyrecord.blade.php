@extends('master')

@section('title','购买记录')
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <link rel="stylesheet" href="{{url('css/buyrecord.css')}}">
   
    
@section('content')
<body>
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">购买记录</strong>
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon"><i class="buycart"></i></a>
    </div>
    @if($status == 1)
    @foreach($orderInfo as $v)
    <div class="recordwrapp">
        <div class="buyrecord-con clearfix">
            <div class="record-img fl">
                <img src="{{url('uploads/hh.gif')}}" alt="">
            </div>
            <div class="record-con fl">
                <h3>订单号：{{$v->order_no}}</h3>
                <div class="clearfix">
                    <div class="win-wrapp fl">
                        <p class="w-time">{{date('Y-m-d H:i:s',$v->create_time)}}</p>
                        <p class="w-chao">总额：￥{{$v->order_amount}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="nocontent">
        <div class="m_buylist m_get">
            <ul id="ul_list">
                <div class="noRecords colorbbb clearfix">
                    <s class="default"></s>您还没有购买商品哦~
                </div>
            </ul>
        </div>
    </div>
    @endif
</body>
@endsection