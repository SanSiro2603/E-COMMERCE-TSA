<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */



public function boot()
{

    Carbon::setLocale('id');

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    date_default_timezone_set('Asia/Jakarta');
    // Status ini hanya mengatur akses autentikasi customer, bukan checkout.
    try {
        View::share('customerLoginEnabled', SystemSetting::isEnabled('customer_login_enabled'));
    } catch (\Exception $e) {
        // Fallback jika database belum siap/migrate
        View::share('customerLoginEnabled', true);
    }

}
}
