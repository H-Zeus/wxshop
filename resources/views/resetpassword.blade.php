<link href="{{url('css/findpwd.css')}}" rel="stylesheet" type="text/css" />
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<body>
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">重置密码</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
<div class="wrapper">
    <div class="registerCon">
        <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
        <input type="hidden" value="{{$user_tel}}" id="user_tel">
        <ul>
            <li>
                <s class="password"></s>
                <input type="password" id="verifcode" placeholder="6-16位数字、字母组成" value="" maxlength="16" />
            </li>
            <li><a id="findPasswordNextBtn" href="javascript:void(0);" class="orangeBtn">确认重置</a></li>
        </ul>
    </div>
</div>

<script src="layui/layui.js"></script> 
<script>
    layui.use(['layer', 'laypage', 'element'], function(){
        var layer = layui.layer
        ,laypage = layui.laypage
        ,element = layui.element(); 
    })
</script>
<script>
    function resetpwd(){
        // 密码失去焦点
        $('#verifcode').blur(function(){
            reg=/^[0-9A-Za-z]{6,16}$/;
            var that = $(this);
            if( that.val()==""|| that.val()=="6-16位数字、字母组成"){   
                layer.msg('请重置密码！');
            }else if(!reg.test(that.val())){
                layer.msg('请输入6-16位数字、字母组成的密码！');
            }
        })
    }
    resetpwd();

    //确认重置
    $('#findPasswordNextBtn').click(function(){
        var user_pwd = $('#verifcode').val();
        var _token = $('#_token').val();
        var user_tel = $('#user_tel').val();
        $.ajax({
            url:"{{url('resetpwd')}}",
            type:'post',
            data:{_token:_token,user_pwd:user_pwd,user_tel:user_tel},
        }).done(function(res){
            layer.msg(res,{time:1000},function(){
                if(res == '修改成功'){
                    location.href="{{url('login')}}";
                }
            });
        })
    })
</script>
</body>