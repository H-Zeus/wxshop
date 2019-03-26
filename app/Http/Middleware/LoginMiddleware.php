<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
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
        //判断是否登录
        if(!session('userInfo')){
            // echo "<script> alert('请先登录');location.href='/userpage';</script>"; 
            return redirect('/userpage');
            // return Redirect::guest('/userpage'); 
        }
        return $next($request);
    }
}
