<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::guard($guard)->user()->user_type =='admin'){
                return redirect('/admin/dashboard');
            }else if(Auth::guard($guard)->user()->user_type =='agent'){
                return redirect('/agent/dashboard');
            }else{
                return redirect('/store/dashboard');
            }


        }

        return $next($request);
    }
}
