<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
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
        if(Auth::check())
        {
            if(Auth::user()->isAdmin())
            {
                return $next($request);
            } else {
                return 'This page for admins only.';
            }
        } else {
            return 'This page for admins only.';
        }
    }
}
