<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            // Jika belum melewati 2FA
            if (!$request->session()->has('2fa_passed')) {
                // Kecualikan rute 2fa itu sendiri agar tidak terjadi redirect loop
                if (!$request->routeIs('2fa.*') && !$request->routeIs('logout')) {
                    return redirect()->route('2fa.index');
                }
            }
        }

        return $next($request);
    }
}
