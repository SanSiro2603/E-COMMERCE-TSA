<?php

namespace Tests\Feature\SuperAdmin;

use App\Helpers\AgentHelper;
use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLogActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_and_logout_events_create_activity_log(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
        ]);

        // Trigger Login Event
        event(new Login('web', $admin, false));

        $this->assertDatabaseHas('admin_logs', [
            'user_id' => $admin->id,
            'admin_email' => 'admintest@example.com',
            'action' => 'Login',
        ]);

        // Trigger Logout Event
        event(new Logout('web', $admin));

        $this->assertDatabaseHas('admin_logs', [
            'user_id' => $admin->id,
            'admin_email' => 'admintest@example.com',
            'action' => 'Logout',
        ]);
    }

    public function test_agent_helper_parses_xiaomi_device_correctly(): void
    {
        $xiaomiUa = 'Mozilla/5.0 (Linux; Android 12; M2101K6G Build/SKQ1.210908.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Mobile Safari/537.36';

        $parsed = AgentHelper::parse($xiaomiUa);

        $this->assertEquals('Mobile', $parsed['device_type']);
        $this->assertEquals('Android Device', $parsed['device_name']);
        $this->assertEquals('Android', $parsed['operating_system']);
        $this->assertStringContainsString('Google Chrome', $parsed['browser']);
    }

    public function test_customer_login_does_not_create_admin_log(): void
    {
        $customer = User::factory()->create([
            'role' => 'pembeli',
            'email' => 'customer@example.com',
        ]);

        event(new Login('web', $customer, false));

        $this->assertDatabaseMissing('admin_logs', [
            'admin_email' => 'customer@example.com',
        ]);
    }

    public function test_super_admin_can_access_activity_log_page_and_filter(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => 'SECRET123',
        ]);

        AdminLog::create([
            'user_id' => $superAdmin->id,
            'admin_name' => 'Super Admin Test',
            'admin_email' => 'superadmin@example.com',
            'action' => 'Verifikasi 2FA',
            'description' => 'Berhasil verifikasi 2FA',
            'ip_address' => '127.0.0.1',
            'device_type' => 'Mobile',
            'device_name' => 'Xiaomi (M2101K6G)',
            'operating_system' => 'Android 12',
            'browser' => 'Google Chrome',
        ]);

        $response = $this->actingAs($superAdmin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.logs.index', ['search' => 'Xiaomi']));

        $response->assertOk();
        $response->assertSee('Log Aktivitas Sistem');
        $response->assertSee('Xiaomi (M2101K6G)');
    }

    public function test_regular_admin_cannot_access_super_admin_activity_log_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'google2fa_secret' => 'SECRET123',
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true])
            ->get(route('superadmin.logs.index'));

        $response->assertStatus(403);
    }
}
