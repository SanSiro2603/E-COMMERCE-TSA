<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Pembeli\PembeliDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa mendefinisikan semua route aplikasi kamu.
| Route ini akan dimuat oleh RouteServiceProvider dalam group "web".
|
*/

// 🔹 Halaman utama dan gallery (umum)
Route::get('/', [DashboardController::class, 'index'])->name('landing');
Route::get('/gallery-hewan', [DashboardController::class, 'hewan'])->name('gallery.hewan');

// 🔹 Route hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    
});

// 🔹 Logout hanya untuk user login
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


// 🔹 Route untuk dashboard ADMIN & PEMBELI
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
        
    Route::get('/pembeli/dashboard', [PembeliDashboardController::class, 'index'])
        ->name('pembeli.dashboard');
});




