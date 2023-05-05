<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CekLogin
{
    public function handle($request, Closure $next)
    {
        if (Session::has('useractive')) {
            return $next($request);
        } else {
            return redirect('login');
        }
    }
}
