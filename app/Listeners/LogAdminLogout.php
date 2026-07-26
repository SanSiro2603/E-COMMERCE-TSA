<?php

namespace App\Listeners;

use App\Helpers\LogHelper;
use Illuminate\Auth\Events\Logout;

class LogAdminLogout
{
    /**
     * Handle the Logout event.
     */
    public function handle(Logout $event): void
    {
        if ($event->user && in_array($event->user->role, ['admin', 'super_admin'], true)) {
            LogHelper::record('Logout', 'Melakukan logout dari sistem admin', $event->user);
        }
    }
}
