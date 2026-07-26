<?php

namespace Tests\Feature\Auth;

use App\Mail\ResetTwoFactorMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class TwoFactorResetEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_request_2fa_reset_email(): void
    {
        Mail::fake();

        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'superadmin@example.com',
            'google2fa_secret' => 'SECRETKEY1234567',
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('2fa.send-reset-email'));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        Mail::assertSent(ResetTwoFactorMail::class, function ($mail) use ($superAdmin) {
            return $mail->hasTo($superAdmin->email);
        });
    }

    public function test_super_admin_can_reset_2fa_with_valid_signed_url(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => 'SECRETKEY1234567',
        ]);

        $signedUrl = URL::temporarySignedRoute(
            '2fa.reset.confirm',
            now()->addMinutes(15),
            ['user' => $superAdmin->getKey()]
        );

        $response = $this->actingAs($superAdmin)->get($signedUrl);

        $response->assertRedirect(route('2fa.index'));
        $response->assertSessionHas('status');

        $this->assertNull($superAdmin->fresh()->google2fa_secret);
    }

    public function test_regular_admin_cannot_request_2fa_reset_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'google2fa_secret' => 'SECRETKEY1234567',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('2fa.send-reset-email'));

        $response->assertStatus(403);
        Mail::assertNothingSent();
    }

    public function test_invalid_signature_fails_to_reset(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => 'SECRETKEY1234567',
        ]);

        $invalidUrl = route('2fa.reset.confirm', [
            'user' => $superAdmin->getKey(),
            'signature' => 'invalidsignature123',
        ]);

        $response = $this->actingAs($superAdmin)->get($invalidUrl);

        $response->assertStatus(403);
        $this->assertNotNull($superAdmin->fresh()->google2fa_secret);
    }
}
