<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'shop_goods';

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $primaryKey  = 'goods_id';


     /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;

     /**
     * 更改价格显示。
     *
     * @param  string  $value
     * @return string
     */
    public function getSelfPriceAttribute($value)
    {
        return number_format($value,'2','.',',');
    }
}
