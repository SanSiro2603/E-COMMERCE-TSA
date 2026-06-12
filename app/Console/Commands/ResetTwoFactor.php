<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetTwoFactor extends Command
{
    protected $signature = 'security:reset-2fa {email}';

    protected $description = 'Reset 2FA untuk akun admin atau super admin berdasarkan email';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Akun dengan email {$email} tidak ditemukan.");

            return self::FAILURE;
        }

        if (! in_array($user->role, ['admin', 'super_admin'], true)) {
            $this->error('Reset 2FA hanya dapat dilakukan untuk akun admin atau super admin.');

            return self::FAILURE;
        }

        $user->forceFill(['google2fa_secret' => null])->save();

        $this->info("2FA untuk {$user->email} berhasil di-reset.");

        return self::SUCCESS;
    }
}
