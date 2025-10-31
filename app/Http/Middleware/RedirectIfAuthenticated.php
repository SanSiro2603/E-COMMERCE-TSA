<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Arahkan sesuai role user
                $role = Auth::user()->role ?? null;

                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'pembeli') {
                    return redirect()->route('pembeli.dashboard');
                } elseif ($role === 'superadmin') {
                    return redirect()->route('superadmin.dashboard');
                } else {
                    return redirect()->route('landing');
                }
            }
        }

        return $next($request);
    }
}
