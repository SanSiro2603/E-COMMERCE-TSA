<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registrasi user baru
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'role' => ['nullable', 'in:pembeli,admin'], // Default pembeli jika tidak dipilih
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'] ?? 'pembeli', // Default role pembeli
        ]);

        // Trigger event registered (untuk email verification jika diperlukan)
        event(new Registered($user));

        // Auto login setelah registrasi
        Auth::login($user);

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // Redirect berdasarkan role
        return redirect()->intended($this->redirectTo($user->role))
            ->with('success', 'Registrasi berhasil! Selamat datang di Lembah Hijau.');
    }

    /**
     * Redirect berdasarkan role setelah registrasi
     */
    protected function redirectTo($role)
    {
        return match ($role) {
            'super_admin' => route('superadmin.dashboard'),
            'admin'       => route('admin.dashboard'),
            'pembeli'     => route('pembeli.dashboard'),
            default       => route('pembeli.dashboard'),
        };
    }
}