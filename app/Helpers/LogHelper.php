<?php

namespace App\Helpers;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function record($action, $description)
    {
        if (Auth::check()) {
            AdminLog::create([
                'user_id'     => Auth::id(),
                'admin_name'  => Auth::user()->name,
                'admin_email' => Auth::user()->email,
                'action'      => $action,
                'description' => $description,
                'user_agent'  => request()->userAgent(),
            ]);
        }
    }
}
