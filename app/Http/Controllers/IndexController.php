<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Category;
use App\Model\User;
use App\Model\Cart;
use App\Common;
use App\Tools\Captcha;

class IndexController extends Controller
{
    /** 首页 */
    public function index()
    {   
        //获取商品信息
        $goods_model = new Goods;
        $goodsInfo = $goods_model->get();

        //获取分类信息
        $categort_model = new Category;
        $categoryInfo = $categort_model->get();
        $categoryInfo = $this->getTopCateInfo($categoryInfo);
        return view('index',['goodsInfo'=>$goodsInfo,'categoryInfo'=>$categoryInfo]);
    }

    /** 加入购物车 */
    public function cartadd(Request $request)
    {
        $cart_model = new Cart;
        $data = [
            'user_id' => session('userInfo')['user_id'],
            'goods_id' => $request->goods_id
        ];
        //判断购物车中是否已有该商品
        $check = $cart_model->where($data)->first();
        if($check){
            //累加
            $cart_model->where($data)
                    ->update([
                        'buy_number' => $check['buy_number']+1,
                        'status' => 1,
                    ]);
        }else{
            //入库
            $data['create_time'] = time();
            $cart_model->insert($data);
        }
    }

    /** 购物车商品数+1 */
    public function shopcartadd(Request $request)
    {
        $buy_number = $request->buy_number;
        $cart_id = $request->cart_id;
        $cart_model = new Cart;
        //查询是否超出库存
        $inventory = Common::inventory($cart_id,$buy_number);
        if($inventory == '操作错误'){
            return '错误！购买数量已超出库存';
        }
        //商品数量+1
        $where = [
            'user_id' => session('userInfo')['user_id'],
            'cart_id' => $cart_id
        ];
        $res = $cart_model->where($where)->update(['buy_number'=>$buy_number]);
        if($res){
            return '修改成功';
        }else{
            return '修改失败';
        }
    }
    
    /** 购物车商品数-1 */
    public function shopcartmin(Request $request)
    {
        $buy_number = $request->buy_number;
        $cart_model = new Cart;
        $where = [
            'user_id' => session('userInfo')['user_id'],
            'cart_id' => $request->cart_id
        ];
        $res = $cart_model->where($where)->update(['buy_number'=>$buy_number]);
        if($res){
            return '修改成功';
        }else{
            return '修改失败';
        }
    }

    /** 购物车商品数输入框 */
    public function shopcartkey(Request $request)
    {
        $buy_number = $request->buy_number;
        if($buy_number == ''){
            return '错误！购买数量不能为空';
        }
        if($buy_number <= 0){
            return '错误！购买数量必须大于0';
        }
        $cart_id = $request->cart_id;
        $cart_model = new Cart;
        //查询是否超出库存
        $inventory = Common::inventory($cart_id,$buy_number);
        if($inventory == '操作错误'){
            return '错误！购买数量已超出库存';
        }
        //修改商品数量
        $where = [
            'user_id' => session('userInfo')['user_id'],
            'cart_id' => $cart_id
        ];
        $res = $cart_model->where($where)->update(['buy_number'=>$buy_number]);
        if($res){
            return '修改成功';
        }else{
            return '修改失败';
        }
    }

    /** 立即购买 */
    public function ordersupplyment($id)
    {
        $goods_model = new Goods;
        $where = ['goods_id' => $id];
        $goodsInfo = $goods_model->where($where)->first();
        return view('ordersupplyment',['goodsInfo'=>$goodsInfo]);
    }

    /** 所有商品 */
    public function allshops(Request $request,$id='0')
    {
        //获取当前url参数
        $url_id = substr($request->getRequestUri(),'10');
        //获取商品 分类ID
        $categort_model = new Category;
        $categoryInfo = $categort_model->get();
        $goods_model = new Goods;
        if($id != 0){
            $cate_id = $this->getGoodsInfo($categoryInfo,$id);
            //获取商品信息
            $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->get();
        }else{
            //获取商品信息
            $goodsInfo = $goods_model->get();
        }
        //获取顶级分类信息
        $categoryInfo = $this->getTopCateInfo($categoryInfo);

        return view('allshops',['goodsInfo'=>$goodsInfo,'categoryInfo'=>$categoryInfo,'url_id'=>$url_id]);
    }

    /** 搜索 */
    public function search(Request $request,$cate_id='')
    {
        $goods_model = new Goods;
        $keyword = $request->keyword;
        $goodsInfo = [];
        $goodsInfoNum = 0;
        if($cate_id != 0){
            //获取商品 分类ID
            $categort_model = new Category;
            $categoryInfo = $categort_model->get();
            $cate_id = $this->getGoodsInfo($categoryInfo,$cate_id);
            if($keyword != ''){
                //获取商品信息
                $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->where('goods_name','like',"%$keyword%")->get();
                $goodsInfoNum = count($goodsInfo); 
            }
        }else{
            if($keyword != ''){
                //获取商品信息
                $goodsInfo = $goods_model->where('goods_name','like',"%$keyword%")->get();
                $goodsInfoNum = count($goodsInfo); 
            }
        }
        // dd($goodsInfo);  
        return view('search',['goodsInfo'=>$goodsInfo,'goodsInfoNum'=>$goodsInfoNum]);
    }

    /** 根据条件：默认 查询商品信息 */
    public function default($id='')
    {
        $goods_model = new Goods;
        if($id != ''){
            //获取商品 分类ID
            $categort_model = new Category;
            $categoryInfo = $categort_model->get();
            $cate_id = $this->getGoodsInfo($categoryInfo,$id);
            //获取商品信息
            $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->get();
        }else{
            $goodsInfo = $goods_model->get();
        }
        return view('replace',['goodsInfo'=>$goodsInfo]);
    }
    /** 根据条件：最新 查询商品信息 */
    public function newest($id='')
    {
        $goods_model = new Goods;
        if($id != ''){
            //获取商品 分类ID
            $categort_model = new Category;
            $categoryInfo = $categort_model->get();
            $cate_id = $this->getGoodsInfo($categoryInfo,$id);
            //获取商品信息
            $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->orderBy('create_time','desc')->get();
        }else{
            $goodsInfo = $goods_model->orderBy('create_time','desc')->get();
        }
        return view('replace',['goodsInfo'=>$goodsInfo]);
    }
    /** 根据条件：价值 由低到高 查询商品信息 */
    public function up($id='')
    {
        $goods_model = new Goods;
        if($id != ''){
            //获取商品 分类ID
            $categort_model = new Category;
            $categoryInfo = $categort_model->get();
            $cate_id = $this->getGoodsInfo($categoryInfo,$id);
            //获取商品信息
            $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->orderBy('self_price','asc')->get();
        }else{
            $goodsInfo = $goods_model->orderBy('self_price','asc')->get();
        }
        return view('replace',['goodsInfo'=>$goodsInfo]);
    }
    /** 根据条件：价值 由高到低 查询商品信息 */
    public function down($id='')
    {
        $goods_model = new Goods;
        if($id != ''){
            //获取商品 分类ID
            $categort_model = new Category;
            $categoryInfo = $categort_model->get();
            $cate_id = $this->getGoodsInfo($categoryInfo,$id);
            //获取商品信息
            $goodsInfo = $goods_model->whereIn('cate_id',$cate_id)->orderBy('self_price','desc')->get();
        }else{
            $goodsInfo = $goods_model->orderBy('self_price','desc')->get();
        }
        return view('replace',['goodsInfo'=>$goodsInfo]);
    }

    /** 商品详情 */
    public function shopcontent($goods_id)
    {
        //获取商品信息
        $goods_model = new Goods;
        $goodsInfo = $goods_model->where(['goods_id'=>$goods_id])->first();

        //轮播图处理
        $goods_imgs = $goods_model->where(['goods_id'=>$goods_id])->select('goods_imgs')->first()['goods_imgs'];
        $goods_imgs = explode('|', rtrim($goods_imgs,'|'));
        return view('shopcontent',['goodsInfo'=>$goodsInfo,'goods_imgs'=>$goods_imgs]);
    }

    /** 递归获取顶级分类 */
    public function gettopcateinfo($categoryInfo,$pid=0)
    {
        static $info = [];
        foreach($categoryInfo as $v){
            if($v['pid']==$pid){
                $info[] = $v;
            }
        }
        return $info;
    }

    /** 递归获取商品 分类ID*/
    public function getgoodsinfo($categoryInfo,$pid=0)
    {
        static $cate_id = [];
        foreach($categoryInfo as $v){
            if($v['pid']==$pid){
                $cate_id[] = $v['cate_id'];
                $this->getGoodsInfo($categoryInfo,$v['cate_id']);
            }
        }
        return $cate_id;
    }

    /** 购物车 */
    public function shopcart(Request $request)
    {
        if($request->ajax()){
            //行除
            $cart_id = $request->cart_id;
            // dd($cart_id);
            $cart_model = new Cart;
            $res = $cart_model->where(['cart_id' => $cart_id])->update(['status'=>2,'buy_number'=>0]);
            if($res){
                return '删除成功';
            }else{
                return '删除失败';
            }
        }else{
            $cart_model = new Cart;
            //查询当前用户的购物车信息
            $where = [
                'user_id' => session('userInfo')['user_id'],
                'status' => 1
            ];
            $cartInfo = $cart_model
                            ->where($where)
                            ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                            ->get();
            if($cartInfo == '[]'){
                $checkNum = 1;
            }else{
                $checkNum = 2;
            }
            return view('shopcart',['cartInfo'=>$cartInfo,'checkNum'=>$checkNum]);
        }
    }

    /** 批量删除 */
    public function remove(Request $request)
    {
        $cart_id = rtrim($request->cart_id,',');
        $cart_id = explode(',',$cart_id);
        $cart_model = new Cart;
        foreach($cart_id as $v){
            $cart_model->where(['cart_id'=>$v])->update(['status'=>2,'buy_number'=>0]);
        }
    }

    /** 我的雪天 */
    public function userpage()
    {
        //判断登录状态
        if(session('userInfo')){
            $checkLogin = 1;
        }else{
            $checkLogin = 2;
        }
        //获取用户信息
        $userInfo = [
            'user_id' => session('userInfo')['user_id'],
            'user_name' => session('userInfo')['user_name']
        ];
        return view('userpage',['checkLogin'=>$checkLogin,'userInfo'=>$userInfo]);
    }

    /** 设置 */
    public function set(Request $request,$quit=0)
    {
        if($quit=='quit'){
            //退出登录
            $request->session()->flush();
        }
        return view('set');
    }

    /** 收货地址 */
    public function address()
    {
        return view('address');
    }
    /** 添加收货地址 */
    public function writeaddr()
    {
        return view('writeaddr');
    }

    /** 编辑个人资料 */
    public function edituser()
    {
        return view('edituser');
    }

    /** 购物记录 */
    public function buyrecord()
    {
        return view('buyrecord');
    }

    /** 二维码分享 */
    public function invite()
    {
        return view('invite');
    }

    /** 我的钱包 */
    public function mywallet()
    {
        return view('mywallet');
    }

    /** 登录 */
    public function login(Request $request)
    {
        if($request->post()){
            $user_tel = $request->user_tel;
            $user_pwd = $request->user_pwd;
            $keycode = $request->code;
            $code = session('captcha_code');
            //判断验证码是否正确
            if($keycode != $code){
                return '验证码错误';
            }
            $user_model = new User;
            //查询是否存在用户名
            $res = $user_model->where(['user_tel'=>$user_tel])->first();
            if($res){
                //判断密码是否错误
                if($user_pwd === decrypt($res['user_pwd'])){
                    $userInfo = [
                        'user_id' => $res['user_id'],
                        'user_name' => $user_tel
                    ];
                    session(['userInfo' => $userInfo]);
                    return '登录成功';
                }else{
                    return '用户名或密码错误';
                }
            }else{
                return '用户名或密码错误';
            }
        }else{
            return view('login');
        }
    }

    /** 注册 */
    public function register(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            unset($data['_token']);
            $data['user_pwd'] = encrypt($data['user_pwd']);
            $user_model = new User;
            //查询手机号是否唯一
            $user_tel = $data['user_tel'];
            $check = $user_model->where(['user_tel'=>$user_tel])->first();
            if(!empty($check)){
                return '用户名已存在';
            }
            //判断验证码是否正确
            $code = session('sendInfo')['sendCode'];
            $keycode = $data['keycode'];
            if($keycode == $code){
                unset($data['keycode']);
                //入库
                $res = $user_model->insertGetId($data);
                if($res){
                    $userInfo = [
                        'user_id' => $res,
                        'user_name' => $user_tel
                    ];
                    session(['userInfo' => $userInfo]);
                    return '注册成功';
                }else{
                    return '注册失败';
                }
            }else{
                return '验证码错误';
            }
        }else{
            return view('register');
        }
    }

    /** 发送短信 */
    public function sendsms(Request $request){
        $user_tel = $request->user_tel;
        //随机生成验证码
        $code = Common::createcode(4);
        //发送短信
        $res = Common::sendSms($user_tel,$code);
        if($res){
            $sendInfo = [
                'sendTime' => time(),
                'sendCode' => $code,
                'sendTel' => $user_tel
            ];
            session(['sendInfo'=>$sendInfo]);
            return '发送成功'.'验证码：'.session('sendInfo')['sendCode'];
        }else{
            return '发送失败';
        }
    }
}