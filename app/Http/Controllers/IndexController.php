<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Category;
use App\Model\User;
use App\Model\Cart;
use App\Common;
use App\Model\Address;
use Illuminate\Support\Facades\DB;
use Validator;

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
        //查询出当前商品的购物车商品数量
        $buy_number = $cart_model
                        ->where(['goods_id'=>$request->goods_id,'user_id'=>session('userInfo')['user_id']])
                        ->select('buy_number')
                        ->first()['buy_number'];
        //查询出当前商品库存
        $goods_model = new Goods;
        $goods_num = $goods_model
                    ->where(['goods_id'=>$request->goods_id])
                    ->select('goods_num')
                    ->first()['goods_num'];
        //判断是否超出库存
        if($buy_number >= $goods_num){
            return '库存不足';
        }else{
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

    /** 点击-立即购买 */
    public function ordersupplyment($id)
    {
        //获取商品数据
        $goods_model = new Goods;
        $where = ['goods_id' => $id];
        $goodsInfo = $goods_model->where($where)->first();
        //获取收货地址数据
        $address_model = new Address;
        $user_id = session('userInfo')['user_id'];
        $addressInfo = $address_model->where(['is_default'=>1,'user_id'=>$user_id])->first();
        if($addressInfo == ''){
            $addressInfo= '[]';
        }
        return view('ordersupplyment',['goodsInfo'=>$goodsInfo,'addressInfo'=>$addressInfo]);
    }
    /** 点击-去订单 */
    public function ordersum($id)
    {
        $cart_id = $id;
        $cart_id = $id = rtrim($cart_id,',');
        $cart_id = explode(',',$cart_id);
        //获取商品数据
        $cart_model = new Cart;
        $goodsInfo = $cart_model
                    ->whereIn('cart_id',$cart_id)
                    ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                    ->get();
                    // dd($goodsInfo);
        //获取收货地址数据
        $address_model = new Address;
        $user_id = session('userInfo')['user_id'];
        $addressInfo = $address_model->where(['is_default'=>1,'user_id'=>$user_id])->first();
        if($addressInfo == ''){
            $addressInfo= '[]';
        }
        return view('ordernum',['goodsInfo'=>$goodsInfo,'addressInfo'=>$addressInfo,'id'=>$id]);
    }
    /** 点击-确认地址 */
    public function payment($id)
    {
        if(strpos($id,',') !== false){
            //多商品
            $cart_id = explode(',',$id);
            $cart_model = new Cart;
            $goodsInfo = $cart_model
                        ->whereIn('cart_id',$cart_id)
                        ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                        ->get();
            return view('payments',['goodsInfo'=>$goodsInfo]);
        }else{
            //单商品
            //获取商品数据
            $where = [
                'goods_id' => $id,
            ];
            $goodsInfo = DB::table('shop_goods')
                        ->where($where)
                        ->first();
            //购买件数 固定1
            $goodsInfo->buy_number = 1;
            return view('payment',['goodsInfo'=>$goodsInfo]);
        }
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

    /** 我的信息 */
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
    public function set()
    {
        return view('set');
    }
    /** 安全设置 */
    public function safeset()
    {
        return view('safeset');
    }
    /** 修改登录密码 */
    public function loginpwd(Request $request)
    {
        if($request->ajax()){
            $old_pwd = $request->old_pwd;
            $user_pwd = $request->user_pwd;
            $user_repwd = $request->user_repwd;

            $validate = Validator::make($request->all(),[
                'old_pwd'=>"required",
                'user_pwd'=>"required|min:6|max:12",
                'user_repwd'=>"required"
            ],[
                "old_pwd.required"=>'当前密码为空',
                "user_pwd.required"=>'新密码不为空',
                "user_pwd.min"=>"新密码不能小于6位",
                "user_pwd.max"=>"新密码不能大于12位",
                "user_repwd.required"=>"确认新密码为空",
            ]);
            static $str = '';
            if($validate->fails()){
                $errors  = $validate->errors()->getMessages();
                foreach ($errors as $v){
                    $str .= implode('&&',$v)."<br>";
                }
                return $str;
            }
            //验证
            if($old_pwd == ''){return '当前密码为空！！';}
            if($user_pwd == ''){return '新密码为空！！';}
            $reg = "/^[a-zA-Z0-9]{6,16}$/";
            if(!preg_match($reg,$user_pwd)){return '请输入6-16位数字、字母组成的新密码';}
            if($user_repwd == ''){return '确认新密码为空！！';}
            if($user_pwd !== $user_repwd){return '新密码与确认密码不一致！！';}
            //从数据库中取出当前密码 并解析 对比
            $user_model = new User;
            $user_id = session('userInfo')['user_id'];
            $db_user_pwd = decrypt($user_model
                            ->where(['user_id'=>$user_id])
                            ->select('user_pwd')
                            ->first()['user_pwd']);
            if($old_pwd !== $db_user_pwd){return '密码错误！！';}
            //修改密码
            $res = $user_model->where(['user_id'=>$user_id])->update(['user_pwd'=>encrypt($user_pwd)]);
            if($res){
                return '修改成功';
            }else{
                return '修改失败';
            }
        }else{
            //获取当前账号
            $user_id = session('userInfo')['user_id'];
            $user_model = new User;
            $userInfo = $user_model->where(['user_id'=>$user_id])->first();
            $user_tel = $userInfo->user_tel;
            $left = substr($user_tel,0,3);
            $right = substr($user_tel,7,4);
            $userInfo['user_tel'] = $left.'****'.$right;
            return view('mody-loginpwd',['userInfo'=>$userInfo]);
        }
    }
    /** 退出登录 */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/userpage');
    }

    /** 收货地址 */
    public function address()
    {
        $address_model = new Address;
        $addressInfo = $address_model
                ->where(['user_id'=>session('userInfo')['user_id'],'address_status'=>1])
                ->get();
        return view('address',['addressInfo'=>$addressInfo]);
    }
    /** 添加收货地址 */
    public function writeaddr(Request $request)
    {
        if($request->ajax()){
            if($request->address_name == ''){
                return '收货人不可为空!';
            }
            if($request->address_tel == ''){
                return '手机号码不可为空!';
            }
            if($request->address == ''){
                return '所在区域不可为空!';
            }
            if($request->address_detail == ''){
                return '详细地址不可为空!';
            }
            $data = $request->all();
            unset($data['_token']);
            $user_id = session('userInfo')['user_id'];
            $data['user_id'] = $user_id;
            $address_model = new Address;
            //判断是否为默认地址 1是  2否
            if($data['is_default'] == 1){
                $address_model
                    ->where(['user_id'=>$user_id])
                    ->update(['is_default'=>2]);
                $res = $address_model->insert($data);
                if($res){
                    return '保存成功';
                }else{
                    return '保存失败';
                }
            }else{
                
                $res = $address_model
                        ->insert($data);
                if($res){
                    return '保存成功';
                }else{
                    return '保存失败';
                }
            }
        }else{
            return view('writeaddr');
        }
    }
    /** 设置默认收货地址 */
    public function setdefaultaddress(Request $request)
    {
        if($request->address_name == ''){
            return '收货人不可为空!';
        }
        if($request->address_tel == ''){
            return '手机号码不可为空!';
        }
        if($request->address_detail == ''){
            return '详细地址不可为空!';
        }
        $address_id = $request->address_id;
        $user_id = session('userInfo')['user_id'];
        $address_model = new Address;
        //将所有收货地址设置为 非默认
        $address_model
            ->where(['user_id'=>$user_id])
            ->update(['is_default'=>2]);
        //修改收货地址状态
        $address_model
            ->where(['user_id'=>$user_id,'address_id'=>$address_id])
            ->update(['is_default'=>1]);
        return '修改成功';
    }
    /** 删除收货地址 */
    public function deladdress(Request $request)
    {
        $address_id = $request->address_id;
        //判断当前收货地址是否是默认地址  如果是 禁止删除
        $address_model = new Address;
        $res = $address_model->where(['address_id'=>$address_id])->first()['is_default'];
        if($res == 1){
            return '默认地址禁止删除！！';
        }else{
            $res = $address_model->where(['address_id'=>$address_id])->update(['address_status'=>2]);
            if($res){
                return '删除成功';
            }else{
                return '删除失败';
            }
        }
    }
    /** 编辑收货地址 */
    public function writeaddrupdate(Request $request,$id)
    {
        $address_id = $id;
        $address_model = new Address;
        if($request->ajax()){
            $data = $request->all();
            unset($data['_token']);
            if($data['address'] == ''){
                unset($data['address']);
            }
            $user_id = session('userInfo')['user_id'];
            $data['user_id'] = $user_id;
            $res = $address_model
                    ->where(['address_id'=>$address_id])
                    ->update($data);
            if($res){
                return '保存成功';
            }else if($res == 0){
                return '数据未编辑';
            }else{
                return '保存失败';
            }
        }else{
            
            $addressInfo = $address_model->where(['address_id'=>$address_id])->first();
            return view('writeaddrupdate',['addressInfo'=>$addressInfo]);
        }
    }

    /** 编辑个人资料 */
    public function edituser()
    {
        $user_id = session('userInfo')['user_id'];
        $user_model = new User;
        $userInfo = $user_model->where(['user_id'=>$user_id])->first();
        return view('edituser',['userInfo'=>$userInfo]);
    }
    /** 编辑个人资料 */
    public function namemodify(Request $request)
    {
        if($request->ajax()){
            $user_name = $request->user_name;
            $user_id = session('userInfo')['user_id'];
            //修改user表中数据
            $user_model = new User;
            $res = $user_model->where(['user_id'=>$user_id])->update(['user_name'=>$user_name]);
            if($res != 0){
                $userInfo = [
                    'user_id' => $user_id,
                    'user_name' => $user_name
                ];
                session(['userInfo'=>$userInfo]);
                return '修改成功';
            }else{
                return '数据无改动';
            }
        }else{
            $user_name = session('userInfo')['user_name'];
            return view('nicknamemodify',['user_name'=>$user_name]);
        }
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
            $validate = Validator::make($request->all(),[
                'user_tel'=>"required",
                'user_pwd'=>"required|min:6|max:12",
                'code'=>"required"
            ],[
                "user_tel.required"=>'手机号为空',
                "user_pwd.required"=>'密码为空',
                "user_pwd.min"=>"密码不能小于6位",
                "user_pwd.max"=>"密码不能大于12位",
                "code.required"=>"验证码为空",
            ]);
            static $str = '';
            if($validate->fails()){
                $errors  = $validate->errors()->getMessages();
                foreach ($errors as $v){
                    $str .= implode('&&',$v)."<br>";
                }
                return $str;
            }
            $user_tel = $request->user_tel;
            $user_pwd = $request->user_pwd;
            $keycode = $request->code;
            //验证
            if($user_tel == ''){return '手机号不能为空';}
            if($user_pwd == ''){return '密码不能为空';}
            if($keycode == ''){return '验证码不能为空';}
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
    /** 找回密码 */
    public function findpwd(Request $request)
    {
        if($request->ajax()){
            $validate = Validator::make($request->all(),[
                'user_tel'=>"required",
                'keycode'=>"required"
            ],[
                "user_tel.required"=>'手机号为空',
                "keycode.required"=>"验证码为空",
            ]);
            static $str = '';
            if($validate->fails()){
                $errors  = $validate->errors()->getMessages();
                foreach ($errors as $v){
                    $str .= implode('&&',$v)."<br>";
                }
                return $str;
            }
            $keycode = $request->keycode;
            $code = session('sendInfo')['sendCode'];
            //判断验证码是否正确
            if($keycode === $code){
                return '验证码正确';
            }else{
                return '验证码错误';
            }
        }else{
            return view('findpwd');
        }
    }
    /** 重置密码页面 */
    public function resetpassword(Request $request)
    {
        $old_tel = $request->old_tel;
        $user_tel = session('sendInfo')['sendTel'];
        return view('resetpassword',['user_tel'=>$user_tel]);
        if($old_tel === $user_tel){
            return view('resetpassword');
        }else{
            return '<h1>请合法操作<h1>';
        }
    }
    /** 确认重置 */
    public function resetpwd(Request $request)
    {
        $user_pwd = encrypt($request->user_pwd);
        $user_tel = $request->user_tel;
        $user_model = new User;
        $res = $user_model->where(['user_tel'=>$user_tel])->update(['user_pwd'=>$user_pwd]);
        if($res){
            return '修改成功';
        }else{
            return '修改失败';
        }
    }

    /** 注册 */
    public function register(Request $request)
    {
        if($request->ajax()){
            $validate = Validator::make($request->all(),[
                'user_tel'=>"required|unique:shop_user",
                'user_pwd'=>"required|min:6|max:12",
                'keycode'=>"required"
            ],[
                "user_tel.required"=>'手机号为空',
                "user_tel.unique"=>'手机号已存在',
                "user_pwd.required"=>'密码为空',
                "user_pwd.min"=>"密码不能小于6位",
                "user_pwd.max"=>"密码不能大于12位",
                "keycode.required"=>"验证码为空",
            ]);
            static $str = '';
            if($validate->fails()){
                $errors  = $validate->errors()->getMessages();
                foreach ($errors as $v){
                    $str .= implode('&&',$v)."<br>";
                }
                return $str;
            }
            //验证
            if($request->user_tel == ''){
                return '手机号不能为空';
            }
            if($request->user_pwd == ''){
                return '密码不能为空';
            }
            if($request->keycode == ''){
                return '验证码不能为空';
            }
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
                $data['user_name'] = $user_tel;
                //入库
                $res = $user_model->insertGetId($data);
                if($res){
                    $userInfo = [
                        'user_id' => $res,
                        'user_name' => $data['user_name']
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
        $user_pwd = $request->user_pwd;
        $user_tel = $request->user_tel;
        if($user_tel == ''){
            return '手机号不能为空';
        }
        //查询手机号是否唯一
        $user_model = new User;
        $check = $user_model->where(['user_tel'=>$user_tel])->first();
        if(!empty($check)){
            return '用户名已存在';
        }
        if($user_pwd == ''){
            return '密码不能为空';
        }
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
            return '发送成功';
        }else{
            return '发送失败';
        }
    }
    /** 找回密码-发送短信 */
    public function sendsmspwd(Request $request){
        $user_tel = $request->user_tel;
        if($user_tel == ''){return '手机号不能为空';}
        $reg = '/^[0-9]{11}$/';
        if(!preg_match($reg,$user_tel)){return '手机号格式错误';}

        //查询手机号是否存在
        $user_model = new User;
        $check = $user_model->where(['user_tel'=>$user_tel])->first();
        if(empty($check)){
            return '手机号不存在';
        }
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
            return '发送成功';
        }else{
            return '发送失败';
        }
    }
}