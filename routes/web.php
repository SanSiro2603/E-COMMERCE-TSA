<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// AUTH
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// ADMIN
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReportController;

// PEMBELI
use App\Http\Controllers\Pembeli\PembeliDashboardController;
use App\Http\Controllers\Pembeli\ProdukController;
use App\Http\Controllers\Pembeli\CartController;
use App\Http\Controllers\Pembeli\PesananController;
use App\Http\Controllers\Pembeli\PaymentController;
use App\Http\Controllers\Pembeli\AddressController;

// LAINNYA
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\MidtransController;

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
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

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
| ADMIN ROUTES (ADMIN & SUPER ADMIN SAJA 🔒)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Produk
        Route::resource('products', ProductController::class);

        // Kategori
        Route::resource('categories', CategoryController::class);

        // Pesanan
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
        Route::get('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
    });

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (TANPA AUTH)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])
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
            Route::post('/store', [PesananController::class, 'store'])->name('store');
            Route::get('/{order}', [PesananController::class, 'show'])->name('show');
            Route::get('/{order}/edit', [PesananController::class, 'edit'])->name('edit');
            Route::put('/{order}', [PesananController::class, 'update'])->name('update');
            Route::post('/{order}/cancel', [PesananController::class, 'cancel'])->name('cancel');
            Route::post('/{order}/complete', [PesananController::class, 'complete'])->name('complete');
        });

        // Pembayaran
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
            Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
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
        Route::post('alamat/{alamat}/default', [AddressController::class, 'setDefault'])->name('alamat.default');

        // Profil
        Route::get('/profil', fn () => inertia('Pembeli/Profil'))->name('profil.edit');
    });
