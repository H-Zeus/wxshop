<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $primaryKey  = 'm_id';


     /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;
}
