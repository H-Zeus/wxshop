<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use function GuzzleHttp\json_decode;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MenuController extends Controller
{
    /**
     * @content 自定义菜单
     */
    public function index()
    {
        //获取一级菜单信息
        $info = DB::table('menu')->get();
        if(Redis::exists('MenuInfo')){
            $data = Redis::get('MenuInfo');
        }else{
            $token = Wechat::GetAccessToken();
            $menu_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token";
            $data = file_get_contents($menu_url);
            Redis::set('MenuInfo',$data);
        }
        return view('admin.customize',['data'=>$data,'info'=>$info]);
    }

    /**
     * @content 编辑菜单
     */
    public function editMenu(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            unset($data['_token']);
            $m_ids = explode('||',$data['m_id']);array_shift($m_ids);array_pop($m_ids);
            $pids = explode('||',$data['pid']);array_shift($pids);array_pop($pids);
            $names = explode('||',$data['name']);array_shift($names);array_pop($names);
            $types = explode('||',$data['type']);array_shift($types);array_pop($types);
            $keys = explode('||',$data['key']);array_shift($keys);array_pop($keys);
            $urls = explode('||',$data['url']);array_shift($urls);array_pop($urls);
            $statuss = explode('||',$data['status']);array_shift($statuss);array_pop($statuss);
            $number = count($names); //数据总条数
            $datas = [];
            DB::table('menu')->truncate();
            for($i = 0 ;$i<$number;$i++){
                if($keys[$i] == 'undefined' || $keys[$i] == '' || $keys[$i] == 'on'){$keys[$i] = null;}
                if($urls[$i] == 'undefined' || $urls[$i] == ''){$urls[$i] = null;}
                if($statuss[$i] == 'true'){
                    $statuss[$i] = 1;
                }else{
                    $statuss[$i] = 2;
                }
                if($types[$i] == 'undefined'){
                    $types[$i] = null;
                }
                $datas[] = $data = [
                    'pid' => $pids[$i],
                    'name' => $names[$i],
                    'type' => $types[$i],
                    'key' => $keys[$i],
                    'url' => $urls[$i],
                    'status' => $statuss[$i],
                ];
                DB::table('menu')->insert($data);
            }
            $info = $this->getMenu($datas);
            $token = Wechat::GetAccessToken();
            if($info == null){
                $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$token";
            }else{
                $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
                $info = json_encode(['button' =>$info],JSON_UNESCAPED_UNICODE);
            }
            Wechat::HttpPost($url,$info);
            Redis::del('MenuInfo'); //删除菜单信息
            return '编辑成功';
        }else{
            $info = DB::table('menu')->get();
            $type = DB::table('menu_type')->get();
            return view('admin.editmenu',['info'=>$info,'type'=>$type]);
        }
    }

    /**
     * 伪递归 循环拼接数据
     */
    public static function getMenu($data,$pid=0)
    {
        static $info = [];
        foreach($data as $k=>$v){
            if($v['status'] == 1){
                if($v['pid'] == $pid){
                    unset($v['pid']);unset($v['status']);
                    if($v['type']==null){unset($v['type']);}
                    if($v['url']==null){unset($v['url']);}
                    if($v['key']==null){unset($v['key']);}
                    $info[] = $v;
                    foreach($data as $value){
                        if($value['pid'] == $k+1){
                            unset($value['pid']);unset($value['status']);
                            if($value['url']==null){unset($value['url']);}
                            if($value['key']==null){unset($value['key']);}
                            $info[$k]['sub_button'][] = $value;
                        }
                    }
                }
            }
        }
        return $info;
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
        DB::table('menu')->truncate();
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
