<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'shop_order';

    /**
     * 主键ID
     *
     * @var string
     */
    protected $primaryKey  = 'order_id';


     /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 更改状态显示。
     *
     * @param  string  $value
     * @return string
     */
    public function getOrderStatusAttribute($value)
    {
        if($value == 1){
            return '未支付-等待已支付';
        }else if($value == 2){
            return '已支付-等待确认';
        }else if($value == 3){
            return '已确认-等待备货';
        }else if($value == 4){
            return '备货中-等待发货';
        }else if($value == 5){
            return '发货中';
        }else if($value == 6){
            return '已发货';
        }else if($value == 7){
            return '订单完成';
        }
    }
}
