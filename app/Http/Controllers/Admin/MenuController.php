<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use function GuzzleHttp\json_decode;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * @content 自定义菜单
     */
    public function index()
    {
        //获取一级菜单信息
        $info = DB::table('menu')->get();
        // $token = Wechat::GetAccessToken();
        // $menu_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token";
        // $data = file_get_contents($menu_url);
        // echo "<pre>";
        // print_r($data);
        // print_r($info);exit;
        $data = 1;
        return view('admin.customize',['data'=>$data,'info'=>$info]);
    }

    /**
     * @content 编辑菜单
     */
    public function editMenu(Request $request)
    {
        if($request->ajax()){
            dd(1);
        }else{
            $info = DB::table('menu')->get();
            $type = DB::table('menu_type')->get();
            return view('admin.editmenu',['info'=>$info,'type'=>$type]);
        }
    }

    /**
     * @content 循环数据入库
     */
    public function cde()
    {
        //获取菜单列表
        $token = Wechat::GetAccessToken();
        $menu_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token";
        $info = json_decode(file_get_contents($menu_url),true)['menu']['button'];
        static $arr1 = [];

        //循环数据入库
        foreach($info as $k=>$v){
            $arr = [];
            $arr[$k]['pid'] = 0;
            $arr[$k]['type'] = empty($v['type'])?null:$v['type'];
            $arr[$k]['name'] = empty($v['name'])?null:$v['name'];
            $arr[$k]['key'] = empty($v['key'])?null:$v['key'];
            $arr[$k]['url'] = empty($v['url'])?null:$v['url'];
            //一级菜单入库
            $m_id = DB::table('menu')->insertGetId($arr[$k]);
            if(count($v['sub_button']) != 0){
                foreach($v['sub_button'] as $key=>$value){
                    $arr[$k]['sub_button'][$key]['pid'] = $m_id;
                    $arr[$k]['sub_button'][$key]['type'] = empty($value['type'])?null:$value['type'];
                    $arr[$k]['sub_button'][$key]['name'] = empty($value['name'])?null:$value['name'];
                    $arr[$k]['sub_button'][$key]['key'] = empty($value['key'])?null:$value['key'];
                    $arr[$k]['sub_button'][$key]['url'] = empty($value['url'])?null:$value['url'];
                }
                //二级菜单入库
                DB::table('menu')->insert($arr[$k]['sub_button']);
            }
        }
    }
}
