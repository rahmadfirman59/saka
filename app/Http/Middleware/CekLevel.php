<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CekLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if ( Session::get('useractive')->level == 'kasir') {
            if(session()->get('admin_temporary') && session()->get('admin_temporary') == 'active'){
                return $next($request);
            }
            return response('unauthorized.', 401);
        }
        
        return $next($request);
    }
}
