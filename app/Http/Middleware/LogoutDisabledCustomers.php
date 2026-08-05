<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutDisabledCustomers
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'pembeli' && ! SystemSetting::isEnabled('customer_login_enabled')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi customer telah ditutup oleh Admin.',
                ], 401);
            }

            return redirect()->route('landing')
                ->with('error', 'Sesi customer Anda telah ditutup oleh Admin.');
        }

        return $next($request);
    }
}
