<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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

            if(Auth::guard('admin')->user()) {

                return redirect(route('admin_dashboard'));
                exit();

            }

            // Check if user has approval_code (group user) or not (solo user)
            $user = Auth::user();
            if($user && !empty($user->approval_code)) {
                // Group user - go to folders/chat
                return redirect(route('account.folders'));
            } else {
                // Solo user - go straight to albums/gallery
                return redirect(route('front.albums'));
            }
        }

        return $next($request);
    }
}
