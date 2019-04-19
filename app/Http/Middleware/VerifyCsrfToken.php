<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/wechat/check',
        '/wechat/test',
        '/admin/uploadfile',
        '/admin/myorder',  //菜单-我的订单
        '/admin/shopcar',  //菜单-我的购物车
        '/admin/shopaddress', //菜单-收货地址
    ];
}
