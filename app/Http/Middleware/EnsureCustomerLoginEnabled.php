<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;

class EnsureCustomerLoginEnabled
{
    public function handle(Request $request, Closure $next)
    {
        if (! SystemSetting::isEnabled('customer_login_enabled')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Login customer sedang dinonaktifkan oleh Admin.',
                ], 403);
            }

            return redirect()->route('landing')
                ->with('error', 'Login customer sedang dinonaktifkan oleh Admin.');
        }

        return $next($request);
    }
}
