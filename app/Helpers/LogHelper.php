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
        $gps = static::getGpsLocation();

        return AdminLog::create([
            'user_id'          => $targetUser->id,
            'admin_name'       => $targetUser->name,
            'admin_email'      => $targetUser->email,
            'action'           => $action,
            'description'      => $description,
            'latitude'         => $gps['latitude'],
            'longitude'        => $gps['longitude'],
            'ip_address'       => static::getRealIp(),
            'device_type'      => $agentInfo['device_type'],
            'device_name'      => $agentInfo['device_name'],
            'operating_system' => $agentInfo['operating_system'],
            'browser'          => $agentInfo['browser'],
            'user_agent'       => $userAgent,
        ]);
    }

    /**
     * Get GPS latitude and longitude from request headers, cookies, session, or inputs.
     */
    public static function getGpsLocation(): array
    {
        $request = request();
        $lat = $request->header('X-GPS-Latitude')
            ?? ($_COOKIE['admin_lat'] ?? null)
            ?? $request->cookie('admin_lat')
            ?? session('admin_lat')
            ?? $request->input('latitude');

        $lng = $request->header('X-GPS-Longitude')
            ?? ($_COOKIE['admin_lng'] ?? null)
            ?? $request->cookie('admin_lng')
            ?? session('admin_lng')
            ?? $request->input('longitude');

        return [
            'latitude'  => (is_numeric($lat) && (float)$lat != 0) ? (float) $lat : null,
            'longitude' => (is_numeric($lng) && (float)$lng != 0) ? (float) $lng : null,
        ];
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
