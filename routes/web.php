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
use App\Http\Controllers\Admin\LandingHomeController;
use App\Http\Controllers\Admin\LandingPageController as AdminLandingPageController;
use App\Http\Controllers\Admin\LandingCatalogController as AdminLandingCatalogController;

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
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LandingCatalogController;

/*
|--------------------------------------------------------------------------
| HALAMAN UMUM
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('landing');
Route::redirect('/home', '/');
Route::get('/home/about', [LandingPageController::class, 'about'])->name('landing.about');
Route::get('/home/catalog', [LandingCatalogController::class, 'index'])->name('landing.catalog');
Route::get('/home/catalog/{slug}', [LandingCatalogController::class, 'show'])->name('landing.catalog.show');
Route::get('/home/information/logistic-delivery', [LandingPageController::class, 'logistic'])->name('landing.information.logistic-delivery');
Route::get('/home/information/procurement-preparation', [LandingPageController::class, 'procurement'])->name('landing.information.procurement-preparation');
Route::get('/home/information/live-export-process', [LandingPageController::class, 'liveExport'])->name('landing.information.live-export-process');
Route::get('/home/future-projects', [LandingPageController::class, 'futureProjects'])->name('landing.future-projects');
Route::get('/home/gallery', [LandingPageController::class, 'gallery'])->name('landing.gallery');
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

Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/2fa/setup/continue', [TwoFactorController::class, 'continueSetup'])->name('2fa.setup.continue');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/send-reset-email', [TwoFactorController::class, 'sendResetEmail'])
        ->name('2fa.send-reset-email')
        ->middleware('throttle:3,1');
    Route::get('/2fa/reset-confirm/{user}', [TwoFactorController::class, 'resetConfirm'])
        ->name('2fa.reset.confirm')
        ->middleware('signed');
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
        Route::get('/search', SearchController::class)->name('search');

        // Produk
        Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
        Route::post('products/bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulk-status');
        Route::resource('products', ProductController::class);

        // Kategori
        Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
        Route::get('categories/check-name', [CategoryController::class, 'checkName'])->name('categories.check-name');
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

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // CMS Landing Page
        Route::prefix('landing')->name('landing.')->group(function () {

            // Home
            Route::get('/home', [LandingHomeController::class, 'index'])->name('home.index');
            Route::post('/home/settings', [LandingHomeController::class, 'updateSettings'])->name('home.settings.update');

            // Hero Slides
            Route::post('/home/slides', [LandingHomeController::class, 'storeSlide'])->name('home.slides.store');
            Route::put('/home/slides/{slide}', [LandingHomeController::class, 'updateSlide'])->name('home.slides.update');
            Route::delete('/home/slides/{slide}', [LandingHomeController::class, 'destroySlide'])->name('home.slides.destroy');
            Route::patch('/home/slides/{slide}/toggle', [LandingHomeController::class, 'toggleSlide'])->name('home.slides.toggle');

            // Catalog Cards
            Route::post('/home/catalog-cards', [LandingHomeController::class, 'storeCatalogCard'])->name('home.catalog-cards.store');
            Route::put('/home/catalog-cards/{card}', [LandingHomeController::class, 'updateCatalogCard'])->name('home.catalog-cards.update');
            Route::delete('/home/catalog-cards/{card}', [LandingHomeController::class, 'destroyCatalogCard'])->name('home.catalog-cards.destroy');
            Route::patch('/home/catalog-cards/{card}/toggle', [LandingHomeController::class, 'toggleCatalogCard'])->name('home.catalog-cards.toggle');

            // Catalog landing (separate from e-commerce products)
            Route::get('/catalog', [AdminLandingCatalogController::class, 'index'])->name('catalog.index');
            Route::post('/catalog/categories', [AdminLandingCatalogController::class, 'storeCategory'])->name('catalog.categories.store');
            Route::put('/catalog/categories/{category}', [AdminLandingCatalogController::class, 'updateCategory'])->name('catalog.categories.update');
            Route::patch('/catalog/categories/{category}/toggle', [AdminLandingCatalogController::class, 'toggleCategory'])->name('catalog.categories.toggle');
            Route::delete('/catalog/categories/{category}', [AdminLandingCatalogController::class, 'destroyCategory'])->name('catalog.categories.destroy');
            Route::post('/catalog/families', [AdminLandingCatalogController::class, 'storeFamily'])->name('catalog.families.store');
            Route::put('/catalog/families/{family}', [AdminLandingCatalogController::class, 'updateFamily'])->name('catalog.families.update');
            Route::patch('/catalog/families/{family}/toggle', [AdminLandingCatalogController::class, 'toggleFamily'])->name('catalog.families.toggle');
            Route::delete('/catalog/families/{family}', [AdminLandingCatalogController::class, 'destroyFamily'])->name('catalog.families.destroy');
            Route::post('/catalog/animals', [AdminLandingCatalogController::class, 'storeAnimal'])->name('catalog.animals.store');
            Route::put('/catalog/animals/{animal}', [AdminLandingCatalogController::class, 'updateAnimal'])->name('catalog.animals.update');
            Route::patch('/catalog/animals/{animal}/toggle', [AdminLandingCatalogController::class, 'toggleAnimal'])->name('catalog.animals.toggle');
            Route::delete('/catalog/animals/{animal}', [AdminLandingCatalogController::class, 'destroyAnimal'])->name('catalog.animals.destroy');
            Route::post('/catalog/animals/{animal}/images', [AdminLandingCatalogController::class, 'storeImage'])->name('catalog.images.store');
            Route::put('/catalog/animals/{animal}/images/{image}', [AdminLandingCatalogController::class, 'updateImage'])->name('catalog.images.update');
            Route::delete('/catalog/animals/{animal}/images/{image}', [AdminLandingCatalogController::class, 'destroyImage'])->name('catalog.images.destroy');

            // Other landing pages
            Route::get('/about', [AdminLandingPageController::class, 'index'])->defaults('page', 'about')->name('about.index');
            Route::get('/information/logistic', [AdminLandingPageController::class, 'index'])->defaults('page', 'information_logistic')->name('information.logistic.index');
            Route::get('/information/procurement', [AdminLandingPageController::class, 'index'])->defaults('page', 'information_procurement')->name('information.procurement.index');
            Route::get('/information/live-export', [AdminLandingPageController::class, 'index'])->defaults('page', 'information_live_export')->name('information.live-export.index');
            Route::get('/future-projects', [AdminLandingPageController::class, 'index'])->defaults('page', 'future_projects')->name('future-projects.index');
            Route::get('/gallery', [AdminLandingPageController::class, 'index'])->defaults('page', 'gallery')->name('gallery.index');

            Route::post('/{page}/settings', [AdminLandingPageController::class, 'updateSettings'])->name('pages.settings.update');
            Route::post('/{page}/{section}/items', [AdminLandingPageController::class, 'storeItem'])->name('pages.items.store');
            Route::put('/{page}/{section}/items/{item}', [AdminLandingPageController::class, 'updateItem'])->name('pages.items.update');
            Route::delete('/{page}/{section}/items/{item}', [AdminLandingPageController::class, 'destroyItem'])->name('pages.items.destroy');
            Route::patch('/{page}/{section}/items/{item}/toggle', [AdminLandingPageController::class, 'toggleItem'])->name('pages.items.toggle');

        });

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

        Route::get('/logs',
            [App\Http\Controllers\SuperAdmin\AdminLogController::class, 'index']
        )->name('logs.index');

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
        Route::get('/produk/search/autocomplete', [ProdukController::class, 'autocomplete'])->name('produk.autocomplete');

        // Keranjang
        Route::prefix('keranjang')->name('keranjang.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/tambah/{product}', [CartController::class, 'tambah'])->name('tambah');
            Route::put('/update/{cart}', [CartController::class, 'update'])->name('update');
            Route::delete('/hapus/{cart}', [CartController::class, 'hapus'])->name('hapus');
            Route::get('/clear', [CartController::class, 'clear'])->name('clear');
            Route::get('/count', [CartController::class, 'count'])->name('count');
            Route::post('/checkout-selected', [CartController::class, 'setCheckoutItems'])->name('checkout-selected');
        });

        // Pesanan
        Route::prefix('pesanan')->name('pesanan.')->group(function () {
            Route::get('/', [PesananController::class, 'index'])->name('index');
            Route::get('/checkout', [PesananController::class, 'checkout'])->name('checkout');
            Route::post('/checkout/shipping-cost', [PesananController::class, 'checkShippingCost'])->name('checkout.shipping_cost');
            Route::post('/buy-now', [PesananController::class, 'buyNow'])->name('buy-now');
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
            Route::post('/{order}/save-method', [PaymentController::class, 'savePaymentMethod'])->name('save-method');
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
