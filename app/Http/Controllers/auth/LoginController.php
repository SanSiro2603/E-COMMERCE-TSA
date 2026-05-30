<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login + validasi reCAPTCHA.
     */
    public function login(Request $request)
    {
        // VALIDASI INPUT + CAPTCHA
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'captcha'], 
        ], [
            'g-recaptcha-response.required' => 'Silakan verifikasi captcha terlebih dahulu.',
            'g-recaptcha-response.captcha' => 'Captcha tidak valid, coba lagi.',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Cegah akun nonaktif masuk ke sistem
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => ['Akun Anda sedang nonaktif. Hubungi Super Admin.'],
                ]);
            }

            // regenerasi session penting untuk keamanan
            $request->session()->regenerate();

            // Paksa verifikasi 2FA ulang setiap login admin/super admin
            if (in_array($user->role, ['admin', 'super_admin'])) {
                $request->session()->forget('2fa_passed');
            }

            return $this->authenticated($request, $user);
        }

        // Jika gagal login
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    /**
     * Redirect setelah berhasil login berdasarkan role.
     */
    protected function authenticated($request, $user)
{
    return match ($user->role) {
        'super_admin' => redirect()->route('superadmin.dashboard')
                            ->with('success', 'Berhasil login! Selamat datang kembali.'),
        
        'admin'       => redirect()->route('admin.dashboard')
                            ->with('success', 'Berhasil login! Selamat datang kembali.'),
        
        'pembeli'     => redirect()->route('pembeli.dashboard')
                            ->with('success', 'Login berhasil! Selamat berbelanja.'),
        
        default       => redirect()->route('landing')
                            ->with('success', 'Login berhasil!'),
    };
}


    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
