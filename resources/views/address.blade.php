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
        <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
        <a href="/" class="m-index-icon">添加</a>
    </div>
    <div class="addr-wrapp">
        <div class="addr-list">
            <ul>
                <li class="clearfix">
                    <span class="fl">兰兰</span>
                    <span class="fr">15034008459</span>
                </li>
                <li>
                    <p>北京市东城区起来我来了</p>
                </li>
                <li class="a-set">
                    <s class="z-set" style="margin-top: 6px;"></s>
                    <span>设为默认</span>
                    <div class="fr">
                        <span class="edit">编辑</span>
                        <span class="remove">删除</span>
                    </div>
                </li>
            </ul>  
        </div>
        <div class="addr-list">
            <ul>
                <li class="clearfix">
                    <span class="fl">兰兰</span>
                    <span class="fr">15034008459</span>
                </li>
                <li>
                    <p>北京市东城区起来我来了</p>
                </li>
                <li class="a-set">
                    <s class="z-defalt" style="margin-top: 6px;"></s>
                    <span>设为默认</span>
                    <div class="fr">
                        <span class="edit">编辑</span>
                        <span class="remove">删除</span>
                    </div>
                </li>
            </ul>  
        </div>
    </div>
</body>
@endsection

<script src="{{url('js/zepto.js')}}" charset="utf-8"></script>
<script src="{{url('js/sm.js')}}"></script>
<script src="{{url('js/sm-extend.js')}}"></script>


@section('my-js')
<!-- 单选 -->
<script>
    // 删除地址
    $(document).on('click','span.remove', function () {
        var buttons1 = [
            {
              text: '删除',
              bold: true,
              color: 'danger',
              onClick: function() {
                $.alert("您确定删除吗？");
              }
            }
          ];
          var buttons2 = [
            {
              text: '取消',
              bg: 'danger'
            }
          ];
          var groups = [buttons1, buttons2];
          $.actions(groups);
    });
    
    var $$=jQuery.noConflict();
    $$(document).ready(function(){
            // jquery相关代码
            $$('.addr-list .a-set s').toggle(
            function(){
                if($$(this).hasClass('z-set')){
                    
                }else{
                    $$(this).removeClass('z-defalt').addClass('z-set');
                    $$(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
                }   
            },
            function(){
                if($$(this).hasClass('z-defalt')){
                    $$(this).removeClass('z-defalt').addClass('z-set');
                    $$(this).parents('.addr-list').siblings('.addr-list').find('s').removeClass('z-set').addClass('z-defalt');
                }
            }
        )
    });
</script>
@endsection