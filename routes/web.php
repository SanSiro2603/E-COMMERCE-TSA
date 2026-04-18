<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

// =========================
// AUTH
// =========================
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// =========================
// ADMIN
// =========================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BiteshipController;

// =========================
// PEMBELI
// =========================
use App\Http\Controllers\Pembeli\PembeliDashboardController;
use App\Http\Controllers\Pembeli\ProdukController;
use App\Http\Controllers\Pembeli\CartController;
use App\Http\Controllers\Pembeli\PesananController;
use App\Http\Controllers\Pembeli\PaymentController;
use App\Http\Controllers\Pembeli\AddressController;
use App\Http\Controllers\Pembeli\ProfileController;

// =========================
// LAINNYA
// =========================
use App\Http\Controllers\RajaOngkirController;

/*
|--------------------------------------------------------------------------
| HALAMAN UMUM
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('landing');
Route::get('/gallery-hewan', [DashboardController::class, 'hewan'])->name('gallery.hewan');

/*
|--------------------------------------------------------------------------
| AUTH GOOGLE
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| AUTH GUEST
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\TwoFactorController;

Route::middleware('auth')->group(function () {
    Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email')
        ->middleware('throttle:5,1');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ADMIN & SUPER ADMIN 🔒)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin', '2fa'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart', [AdminDashboardController::class, 'chartData'])->name('dashboard.chart');
        Route::get('/search', SearchController::class)->name('admin.search');

        // Produk
        Route::resource('products', ProductController::class);

        // Kategori
        Route::resource('categories', CategoryController::class);

        // Pesanan
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Biteship
        Route::post('/orders/{order}/biteship/create', [BiteshipController::class, 'createShipment'])
            ->name('orders.biteship.create');
        Route::get('/orders/{order}/biteship/track', [BiteshipController::class, 'trackShipment'])
            ->name('orders.biteship.track');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
        Route::get('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    });

/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES (KHUSUS SUPER ADMIN 🔒)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin', '2fa'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard',
            [App\Http\Controllers\SuperAdmin\SuperAdminDashboardController::class, 'index']
        )->name('dashboard');

        Route::patch('admins/{admin}/toggle-active',
            [App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'toggleActive']
        )->name('admins.toggle-active');

        Route::patch('admins/{admin}/reset-2fa',
            [App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'resetTwoFactor']
        )->name('admins.reset-2fa');

        Route::patch('admins/{admin}/reset-password',
            [App\Http\Controllers\SuperAdmin\AdminManagementController::class, 'resetPassword']
        )->name('admins.reset-password');

        Route::resource('admins',
            App\Http\Controllers\SuperAdmin\AdminManagementController::class
        );

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',
                [App\Http\Controllers\SuperAdmin\SuperAdminReportController::class, 'index']
            )->name('index');

            Route::get('/export-pdf',
                [App\Http\Controllers\SuperAdmin\SuperAdminReportController::class, 'exportPdf']
            )->name('exportPdf');

            Route::get('/export-excel',
                [App\Http\Controllers\SuperAdmin\SuperAdminReportController::class, 'exportExcel']
            )->name('exportExcel');
        });
    });

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (TANPA AUTH) — arahkan ke PaymentController
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| PEMBELI ROUTES (KHUSUS PEMBELI 🔒)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pembeli'])
    ->prefix('pembeli')
    ->name('pembeli.')
    ->group(function () {

        Route::get('/dashboard', [PembeliDashboardController::class, 'index'])->name('dashboard');

        // Produk
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/{slug}', [ProdukController::class, 'show'])->name('produk.show');

        // Keranjang
        Route::prefix('keranjang')->name('keranjang.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/tambah/{product}', [CartController::class, 'tambah'])->name('tambah');
            Route::put('/update/{cart}', [CartController::class, 'update'])->name('update');
            Route::delete('/hapus/{cart}', [CartController::class, 'hapus'])->name('hapus');
            Route::get('/clear', [CartController::class, 'clear'])->name('clear');
            Route::get('/count', [CartController::class, 'count'])->name('count');
        });

        // Pesanan
        Route::prefix('pesanan')->name('pesanan.')->group(function () {
            Route::get('/', [PesananController::class, 'index'])->name('index');
            Route::get('/checkout', [PesananController::class, 'checkout'])->name('checkout');
            Route::post('/checkout/shipping-cost', [PesananController::class, 'checkShippingCost'])->name('checkout.shipping_cost');
            Route::post('/store', [PesananController::class, 'store'])->name('store');
            Route::get('/{order}', [PesananController::class, 'show'])->name('show');
            Route::get('/{order}/edit', [PesananController::class, 'edit'])->name('edit');
            Route::put('/{order}', [PesananController::class, 'update'])->name('update');
            Route::delete('/{order}/item/{item}', [PesananController::class, 'removeItem'])->name('removeItem');
            Route::patch('/{order}/cancel', [PesananController::class, 'cancel'])->name('cancel');
            Route::patch('/{order}/complete', [PesananController::class, 'complete'])->name('complete');
            Route::get('/{order}/biteship/track', [PesananController::class, 'trackBiteship'])->name('biteship.track');
        });

        // Pembayaran — finish HARUS di atas /{order} agar tidak tertangkap sebagai parameter
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
            Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
            Route::get('/{order}/check-status', [PaymentController::class, 'checkStatus'])->name('check-status');
        });

        // RajaOngkir
        Route::prefix('rajaongkir')->name('rajaongkir.')->group(function () {
            Route::get('/provinces', [RajaOngkirController::class, 'provinces'])->name('provinces');
            Route::get('/cities', [RajaOngkirController::class, 'cities'])->name('cities');
            Route::post('/calculate', [RajaOngkirController::class, 'calculateShipping'])->name('calculate');
        });

        // Alamat
        Route::resource('alamat', AddressController::class)->except(['show']);
        Route::post('alamat/{alamat}/default', [AddressController::class, 'setDefault'])
            ->name('alamat.default');

        // Profil
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::put('/profil/ganti-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    });

/*
|--------------------------------------------------------------------------
| BAHASA
|--------------------------------------------------------------------------
*/
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');