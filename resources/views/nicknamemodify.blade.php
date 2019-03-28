<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>昵称修改</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('layui/css/layui.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{url('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{url('layui/layui.js')}}"></script>
</head>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">昵称修改</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="javascript:void(0)" class="m-index-icon">完成</a>
</div>

<div class="wallet-con">
    <input type="hidden" id="_token" value="{{csrf_token()}}">
    <input type="hidden" id="oldname" value="{{$user_name}}">
    <div class="w-item">
        <input type="text" placeholder="请输入昵称" class="input_key" id="keyword" value="{{$user_name}}"/>
        <i id="clear">x</i>
    </div>
    <p>昵称长度为2-16个字符，由汉字、字母、数字或'_'组成。</p>
</div>

<script>
    $(function(){
        layui.use('layer',function(){
            var status = false;//阻止提交
            $(".input_key").focus();
            // input框
            $("#clear").click(function(){
                $(".input_key").val('');
                $(".input_key").focus();
                $(this).hide();
            });
            if($(".input_key").val().trim()==""){
                $("#clear").hide();
            }
                $("#keyword").focus(function(){
                if ($(".input_key").val().trim()!="") {
                    $("#clear").show();
                }
            });
            $("#keyword").blur(function(){
                if ($(".input_key").val().trim()!="") {
                    $("#clear").show();
                }
                var value = $('.input_key').val();
                var reg = /^[a-zA-Z0-9_\u4e00-\u9fa5]{2,16}$/;
                if(!reg.test(value)){
                    layer.msg("昵称长度为2-16个字符，由汉字、字母、数字或'_'组成");
                    status = false;
                    return false;
                }else{
                    status = true;
                }
            });
            $('#keyword').keydown(function(){
                if($(".input_key").val().trim()!="") {
                    $("#clear").show();
                }else{
                    $("#clear").hide();
                }
            });
            //提交
            $('.m-index-icon').click(function(){
                var _token = $('#_token').val();
                var user_name = $('.input_key').val();
                var oldname = $('#oldname').val();
                if(oldname === user_name){
                    layer.msg('数据无改动');
                    return false;
                }
                if(status == true){
                    $.ajax({
                        url:"{{url('/edituser/namemodify')}}",
                        type:'post',
                        data:{_token:_token,user_name:user_name}
                    }).done(function(res){
                        layer.msg(res,{time:1200},function(){
                            if(res == '修改成功'){
                                location.href="{{url('/edituser')}}";
                            }
                        });
                    })
                }
            })
        })
    });
    // (function($){
    //     $.fn.extend({
    //         insertAtCaret: function(myValue){
    //             var $t=$(this)[0];
    //             if (document.selection){
    //                 this.focus();
    //                 sel = document.selection.createRange();
    //                 sel.text = myValue;
    //                 this.focus();
    //             }else if($t.selectionStart || $t.selectionStart == '0') {
    //                 var startPos = $t.selectionStart;
    //                 var endPos = $t.selectionEnd;
    //                 var scrollTop = $t.scrollTop;
    //                 $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
    //                 this.focus();
    //                 $t.selectionStart = startPos + myValue.length;
    //                 $t.selectionEnd = startPos + myValue.length;
    //                 $t.scrollTop = scrollTop;
    //             }
    //             else {
    //                 this.value += myValue;
    //                 this.focus();
    //             }
    //         }
    //     })  
    // })(jQuery);
    //调用演示
    // $(selector).insertAtCaret("value");
</script>
</body>
</html>
