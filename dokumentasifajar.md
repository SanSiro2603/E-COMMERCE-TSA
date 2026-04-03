# Dokumentasi Implementasi Keamanan & Manajemen Akun

Dokumen ini berisi dokumentasi mengenai penambahan fitur keamanan dan manajemen akun administrator yang telah diimplementasikan pada sistem E-Commerce.

---

## 1. Pembaruan Database & Model (Migrasi)
Untuk mendukung mekanisme OAuth, fitur 2-Factor Authentication (2FA), dan status aktif akun admin, ditambahkan kolom-kolom baru pada tabel `users`.

**File Migration:** `database/migrations/xxxx_xx_xx_xxxxxx_add_security_columns_to_users_table.php`
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('profile_photo');
        $table->boolean('is_active')->default(true)->after('role');
        $table->text('google2fa_secret')->nullable()->after('password');
    });
}
```

**Penyesuaian Model:** `app/Models/User.php`
*Atribut fillable diperbarui agar data baru dapat disimpan.*
```php
protected $fillable = [
    'name', 'email', 'password', 'role', 'phone', 
    'address', 'profile_photo', 'google_id', 
    'google2fa_secret', 'is_active',
];
```

---

## 2. Autentikasi: CAPTCHA
Komponen reCAPTCHA/NoCaptcha yang sebelumnya dikomentari, kini diaktifkan kembali.

**Logika Kontroler:** `app/Http/Controllers/Auth/LoginController.php`
```php
public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        'g-recaptcha-response' => ['required', 'captcha'], // Diaktifkan
    ], [
        'g-recaptcha-response.required' => 'Silakan verifikasi captcha terlebih dahulu.',
        'g-recaptcha-response.captcha' => 'Captcha tidak valid, coba lagi.',
    ]);
    // ...
}
```

**Antarmuka Tampilan:** `resources/views/auth/login.blade.php`
```html
<div class="captcha-wrapper">
    {!! NoCaptcha::display() !!}
</div>
@error('g-recaptcha-response')
    <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p>
@enderror
```

---

## 3. Rate Limiting (Pencegahan Brute Force)
Menambahkan proteksi *Rate Limiter* ke tahap autentikasi lain seperti daftar akun dan lupa password.

**Rute:** `routes/web.php`
```php
Route::middleware('guest')->group(function () {
    // ...
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email')
        ->middleware('throttle:5,1');
});
```

---

## 4. 2-Factor Authentication (TOTP Google Authenticator)
Mewajibkan Admin dan Super Admin menggunakan validasi PIN sekunder setelah kata sandi menggunakan paket `pragmarx/google2fa-laravel` dan `bacon/bacon-qr-code`.

**Pembuatan Middleware:** `app/Http/Middleware/TwoFactorMiddleware.php`
*Mengecek session `2fa_passed` sebelum memberikan akses Dashboard pada Admin & Super Admin.*
```php
public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    if ($user && in_array($user->role, ['admin', 'super_admin'])) {
        if (!$request->session()->has('2fa_passed')) {
            if (!$request->routeIs('2fa.*') && !$request->routeIs('logout')) {
                return redirect()->route('2fa.index');
            }
        }
    }
    return $next($request);
}
```

**Pembuatan Rute dan Pendaftaran Middleware:** `routes/web.php`
```php
// Rute tampilan 2FA UI
Route::middleware('auth')->group(function () {
    Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

// Middleware diterapkan pada grup ini (contoh)
Route::middleware(['auth', 'role:admin,super_admin', '2fa'])
    ->prefix('admin')
    ->group(function () {
        // ...
    });
```

**Logika Kontroler 2FA Utama:** `app/Http/Controllers/Auth/TwoFactorController.php`
```php
public function setup()
{
    $user = Auth::user();

    // Generate secret baru jika belum ada
    if (!$user->google2fa_secret) {
        $google2fa = app('pragmarx.google2fa');
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();
    }

    $google2fa = app('pragmarx.google2fa');
    $qrCodeUrl = $google2fa->getQRCodeInline(config('app.name'), $user->email, $user->google2fa_secret);

    return view('auth.2fa-setup', ['qrCode' => $qrCodeUrl, 'secret' => $user->google2fa_secret]);
}

public function verify(Request $request)
{
    // ... validation
    $valid = app('pragmarx.google2fa')->verifyKey(Auth::user()->google2fa_secret, $request->one_time_password);

    if ($valid) {
        $request->session()->put('2fa_passed', true);
        return redirect()->route('admin.dashboard'); // Redirect sesuai Role
    }
    return back()->withErrors(['one_time_password' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
}
```

---

## 5. Peningkatan Pengelolaan Akun Admin
Fitur kontrol otorisasi yang dipegang Super Admin untuk memonitor Admin lainnya. Antarmuka UI ditambah pada `superadmin.admins.index` dan `superadmin.admins.show`.

**Kontroler Manajemen:** `app/Http/Controllers/SuperAdmin/AdminManagementController.php`
```php
// Mengaktifkan atau Menonaktifkan Akun
public function toggleActive(User $admin)
{
    $admin->update(['is_active' => !$admin->is_active]);
    $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
    return back()->with('success', "Akun admin berhasil {$status}.");
}

// Me-reset kunci OTP 2FA agar admin harus setup scan QR Code ulang
public function resetTwoFactor(User $admin)
{
    $admin->update(['google2fa_secret' => null]);
    return back()->with('success', '2FA untuk admin ini berhasil di-reset.');
}

// Mengganti Manual Password Admin via Form Modal
public function resetPassword(Request $request, User $admin)
{
    $validated = $request->validate([
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $admin->update(['password' => Hash::make($validated['password'])]);
    return back()->with('success', 'Password admin berhasil di-reset.');
}
```

**Antarmuka Indikator Tabel:** `resources/views/superadmin/admins/index.blade.php`
```html
<td class="px-6 py-4 text-center">
    @if($admin->is_active)
    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
    @else
    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Nonaktif</span>
    @endif
</td>
<!-- Tombol Toggle Status -->
<form action="{{ route('superadmin.admins.toggle-active', $admin) }}" method="POST">
    @csrf @method('PATCH')
    <button type="submit">Toggle</button>
</form>
```
