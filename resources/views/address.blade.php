@extends('master')

@section('title','地址管理')
<meta content="app-id=984819816" name="apple-itunes-app" />
<link rel="stylesheet" href="{{url('css/address.css')}}">
<link rel="stylesheet" href="{{url('css/sm.css')}}">    

@section('content')
<body>
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">地址管理</strong>
        <a href="{{url('/userpage')}}" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="{{url('/address/writeaddr')}}" class="m-index-icon">添加</a>
    </div>
    <div class="addr-wrapp">
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        @foreach($addressInfo as $v)
        <div class="addr-list">
            <ul>
                <li class="clearfix">
                    <span class="fl">{{$v->address_name}}</span>
                    <span class="fr">{{$v->address_tel}}</span>
                </li>
                <li>
                    <p>{{$v->address}} {{$v->address_detail}}</p>
                </li>
                <li class="a-set" address_id="{{$v->address_id}}">
                    @if($v->is_default == 1)
                    <s class="z-set" style="margin-top: 1px;margin-right:5px;"></s>
                    @else
                    <s class="z-defalt" style="margin-top: 1px;margin-right:5px;"></s>
                    @endif
                    <span>设为默认</span>
                    <div class="fr">
                        <span class="edit">编辑</span>
                        <span class="remove">删除</span>
                    </div>
                </li>
            </ul>  
        </div>
        @endforeach
    </div>
</body>
@endsection

<script src="{{url('js/zepto.js')}}" charset="utf-8"></script>
<script src="{{url('js/sm.js')}}"></script>
<script src="{{url('js/sm-extend.js')}}"></script>


@section('my-js')
<!-- 单选 -->
<script>
    var _token = $('#_token').val();
    //删除地址
    $(document).on('click','.remove',function(){
        var address_id = $(this).parents('li').attr('address_id');
        var _this = $(this);
        if(_this.parent('div').prev().prev().hasClass('z-set')){
            layer.msg('默认地址禁止删除！！')
        }else{
            layer.msg('您确认要删除此地址吗', {
                time: 10000,
                btn: ['确认','取消'],
                yes: function(index){
                    $.ajax({
                        url:'/address/writeaddr/del',
                        type:'post',
                        data:{_token:_token,address_id:address_id}
                    }).done(function(res){
                        layer.msg(res);
                        if(res == '删除成功'){
                            _this.parents('li').parents("div[class='addr-list']").remove();
                        }
                    })
                    layer.close(index); //如果设定了yes回调，需进行手工关闭
                }
            });
        }
    });
    //编辑地址
    $(document).on('click','.edit',function(){
        var address_id = $(this).parents('li').attr('address_id');
        location.href="{{url('/address/writeaddr/update')}}/"+address_id;
        var _this = $(this);
        // $.ajax({
        //     url:'/address/writeaddr/update',
        //     type:'post',
        //     data:{_token:_token,address_id:address_id}
        // }).done(function(res){
        //     // layer.msg(res);
            
        // })
    })

    // var $=jQuery.noConflict();
    $(document).ready(function(){
        // jquery相关代码
        $('.addr-list .a-set s').toggle(
            function(){
                if($(this).hasClass('z-set')){
                    // console.log(1);
                }else{
                    var address_id = $(this).parent('li').attr('address_id');
                    $.ajax({
                        url:'/address/writeaddr/set',
                        type:'post',
                        data:{_token:_token,address_id:address_id},
                        async:false
                    }).done(function(res){
                        layer.msg(res);
                    })
                    $(this).removeClass('z-defalt').addClass('z-set');
                    $(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
                }   
            },
            function(){
                if($(this).hasClass('z-defalt')){
                    // console.log(3);
                    $(this).removeClass('z-defalt').addClass('z-set');
                    $(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
                }
            }
        )
    });
</script>
@endsection