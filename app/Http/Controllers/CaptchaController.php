<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Captcha;

class CaptchaController extends Controller
{
    /** 创建验证码 */
    public function create()
    {
        $verify = new Captcha();
        $code = $verify->create();
        return $code;
    }
}
