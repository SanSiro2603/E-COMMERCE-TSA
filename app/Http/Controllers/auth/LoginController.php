<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // penting agar session baru terbentuk
            $user = Auth::user();

            // redirect sesuai role
            return $this->authenticated($request, $user);
        }

        // Jika gagal login
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    protected function authenticated($request, $user)
    {
        // Redirect sesuai role
        return match ($user->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'pembeli' => redirect()->route('pembeli.dashboard'),
            default => redirect()->route('landing'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
