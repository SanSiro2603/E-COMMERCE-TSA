<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

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
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    date_default_timezone_set('Asia/Jakarta');
    // === 2. TAMBAHAN BARU (Logika Toko Tutup) ===
    try {
        $setting = SystemSetting::where('key', 'shopping_enabled')->first();
        // Kalau setting ketemu dan value '1', berarti aktif.
        // Kalau setting tidak ketemu (null), kita anggap aktif (true) biar aman.
        $shoppingEnabled = $setting ? $setting->value === '1' : true;
        View::share('shoppingEnabled', $shoppingEnabled);
    } catch (\Exception $e) {
        // Fallback jika database belum siap/migrate
        View::share('shoppingEnabled', true);
    }

}
}
