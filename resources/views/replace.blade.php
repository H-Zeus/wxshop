@foreach($goodsInfo as $v)
<li id="23468">
    <span class="gList_l fl">
        <img class="lazy" src='{{url("/uploads/goodsimg/$v->goods_img")}}'>
    </span>
    <div class="gList_r">
        <h3 class="gray6"><a href="{{url('shopcontent')}}/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
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
            <a codeid="12785750" class="cartadd" goods_id="{{$v->goods_id}}" canbuy="646"><s></s></a>
        </div>
    </div>
</li>
@endforeach