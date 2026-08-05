<?php

namespace Tests\Feature\Auth;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class CustomerLoginAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // LoginController already has a dedicated captcha test suite elsewhere.
        // Keep these tests focused on customer availability behavior.
        Validator::extend('captcha', fn () => true);
    }

    public function test_disabled_customer_login_hides_public_entrypoints(): void
    {
        $this->setCustomerLogin(false);

        $this->get(route('landing'))
            ->assertOk()
            ->assertDontSee('href="' . route('login') . '"', false);

        $this->get(route('gallery.hewan'))
            ->assertOk()
            ->assertDontSee('href="' . route('login') . '"', false);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Login customer sedang ditutup oleh Admin.')
            ->assertDontSee('Sign in with Google')
            ->assertDontSee('Register')
            ->assertDontSee('Forgot Password?');
    }

    public function test_disabled_customer_auth_entrypoints_are_blocked(): void
    {
        $this->setCustomerLogin(false);

        foreach ([
            route('register'),
            route('password.request'),
            route('google.redirect'),
            route('google.callback'),
            route('password.reset', ['token' => 'expired-token']),
        ] as $url) {
            $this->get($url)
                ->assertRedirect(route('landing'))
                ->assertSessionHas('error', 'Login customer sedang dinonaktifkan oleh Admin.');
        }
    }

    public function test_disabled_customer_password_login_is_rejected(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@example.test',
            'password' => bcrypt('password123'),
            'role' => 'pembeli',
        ]);
        $this->setCustomerLogin(false);

        $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password123',
            'g-recaptcha-response' => 'test-token',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_still_login_when_customer_login_is_disabled(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
        $this->setCustomerLogin(false);

        $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password123',
            'g-recaptcha-response' => 'test-token',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_existing_customer_is_logged_out_on_next_request(): void
    {
        $customer = User::factory()->create(['role' => 'pembeli']);
        $this->setCustomerLogin(false);

        $this->actingAs($customer)
            ->get(route('landing'))
            ->assertRedirect(route('landing'))
            ->assertSessionHas('error', 'Sesi customer Anda telah ditutup oleh Admin.');

        $this->assertGuest();
    }

    public function test_admin_can_update_customer_login_setting(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->post(route('admin.settings.update'), [
                'customer_login_enabled' => '0',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Akses login customer berhasil diperbarui!');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'customer_login_enabled',
            'value' => '0',
        ]);
    }

    public function test_reenabling_customer_login_restores_customer_entrypoints(): void
    {
        $this->setCustomerLogin(true);

        $this->get(route('register'))->assertOk();
        $this->get(route('password.request'))->assertOk();
    }

    private function setCustomerLogin(bool $enabled): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'customer_login_enabled'],
            ['value' => $enabled ? '1' : '0']
        );

        View::share('customerLoginEnabled', $enabled);
    }
}
