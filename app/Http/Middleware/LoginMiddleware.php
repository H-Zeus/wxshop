<?php

namespace App\Http\Middleware;

use Closure;

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
            return redirect('userpage');
        }
        return $next($request);
    }
}
