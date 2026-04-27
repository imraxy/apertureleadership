<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        if (Auth::guard('admin')->check()) {
                
            if (Auth::guard('admin')->user()->is_role==1) {

                return $next($request);
            }

            return abort(403);

        }
        
        return redirect(route('admin_login'));
    }
}
