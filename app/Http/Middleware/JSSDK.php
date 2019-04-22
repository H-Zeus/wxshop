<?php

namespace App\Http\Middleware;

use Closure;
use App\Tools\jssdk\Wxjs;

class JSSDK
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
        $jssdk = new Wxjs;
        $signPackage = $jssdk->getSignPackage();
        $wxconfig = ['signPackage'=>$signPackage];
        $request->merge($wxconfig);
        return $next($request);
    }
}
