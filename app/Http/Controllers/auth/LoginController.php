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

            // regenerasi session penting untuk keamanan
            $request->session()->regenerate();

            $user = Auth::user();
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
