<?php

namespace App\Listeners;

use App\Helpers\LogHelper;
use Illuminate\Auth\Events\Login;

class LogAdminLogin
{
    /**
     * Handle the Login event.
     */
    public function handle(Login $event): void
    {
        if ($event->user && in_array($event->user->role, ['admin', 'super_admin'], true)) {
            LogHelper::record('Login', 'Berhasil login ke dalam sistem admin', $event->user);
        }
    }
}
