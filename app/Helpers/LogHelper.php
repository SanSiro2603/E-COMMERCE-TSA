<?php

namespace App\Helpers;

use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    /**
     * Record an activity log entry for Admin or Super Admin.
     *
     * @param string $action
     * @param string $description
     * @param User|null $user
     * @return AdminLog|null
     */
    public static function record(string $action, string $description, ?User $user = null): ?AdminLog
    {
        $targetUser = $user ?? Auth::user();

        if (! $targetUser) {
            return null;
        }

        // Only log activity for admin or super_admin
        if (! in_array($targetUser->role, ['admin', 'super_admin'], true)) {
            return null;
        }

        $userAgent = request()->userAgent();
        $agentInfo = AgentHelper::parse($userAgent);

        return AdminLog::create([
            'user_id'          => $targetUser->id,
            'admin_name'       => $targetUser->name,
            'admin_email'      => $targetUser->email,
            'action'           => $action,
            'description'      => $description,
            'ip_address'       => static::getRealIp(),
            'device_type'      => $agentInfo['device_type'],
            'device_name'      => $agentInfo['device_name'],
            'operating_system' => $agentInfo['operating_system'],
            'browser'          => $agentInfo['browser'],
            'user_agent'       => $userAgent,
        ]);
    }

    /**
     * Get the real client IP, respecting common proxy/CDN headers.
     * Priority: Cloudflare → X-Real-IP → X-Forwarded-For → REMOTE_ADDR
     */
    public static function getRealIp(): string
    {
        $request = request();

        // 1. Cloudflare: CF-Connecting-IP
        if ($ip = $request->header('CF-Connecting-IP')) {
            return trim(explode(',', $ip)[0]);
        }

        // 2. Nginx / load balancer: X-Real-IP
        if ($ip = $request->header('X-Real-IP')) {
            return trim($ip);
        }

        // 3. Standard proxy header: X-Forwarded-For (first IP = client)
        if ($ip = $request->header('X-Forwarded-For')) {
            return trim(explode(',', $ip)[0]);
        }

        // 4. Fallback: direct connection IP
        return $request->ip() ?? '0.0.0.0';
    }
}
