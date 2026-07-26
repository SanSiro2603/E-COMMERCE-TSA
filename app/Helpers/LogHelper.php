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
            'ip_address'       => request()->ip(),
            'device_type'      => $agentInfo['device_type'],
            'device_name'      => $agentInfo['device_name'],
            'operating_system' => $agentInfo['operating_system'],
            'browser'          => $agentInfo['browser'],
            'user_agent'       => $userAgent,
        ]);
    }
}
