<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\Auth\TwoFactorController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_without_2fa_secret_sees_qr_even_when_old_pending_session_exists(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'google2fa_secret' => null,
        ]);

        $response = $this->actingAs($admin)
            ->withSession([
                TwoFactorController::SETUP_SECRET_SESSION_KEY => 'JBSWY3DPEHPK3PXP',
                TwoFactorController::SETUP_USER_ID_SESSION_KEY => $admin->getKey(),
                TwoFactorController::SETUP_PENDING_SESSION_KEY => true,
            ])
            ->get(route('2fa.index'));

        $response->assertOk();
        $response->assertSee('Setup 2-Factor Authentication');
        $response->assertDontSee('Konfirmasi Setup 2FA');
    }

    public function test_continue_setup_shows_code_once_then_returns_to_qr_while_secret_is_empty(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'google2fa_secret' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('2fa.index'))
            ->assertOk()
            ->assertSee('Setup 2-Factor Authentication');

        $this->post(route('2fa.setup.continue'))
            ->assertRedirect(route('2fa.index'));

        $this->get(route('2fa.index'))
            ->assertOk()
            ->assertSee('Konfirmasi Setup 2FA');

        $this->assertNull($admin->fresh()->google2fa_secret);

        $this->get(route('2fa.index'))
            ->assertOk()
            ->assertSee('Setup 2-Factor Authentication')
            ->assertDontSee('Konfirmasi Setup 2FA');
    }
}
