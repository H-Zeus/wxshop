<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Redis::exists('userInfo')){
            $userInfo = json_decode(Redis::get('userInfo'),true);
            session(['userInfo' => $userInfo]);
        }else if(!session('userInfo') && !Redis::exists('userInfo')){
            //判断是否登录
            return redirect('/userpage');
        }
        
        return $next($request);
    }
}
