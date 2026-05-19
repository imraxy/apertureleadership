<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureGroupSession
{
    /**
     * Group sessions use folders and chat; solo users browse the gallery only.
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && empty($user->approval_code)) {
            return redirect()->route('front.albums');
        }

        return $next($request);
    }
}
