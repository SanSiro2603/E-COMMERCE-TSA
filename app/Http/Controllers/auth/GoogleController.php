<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Jika belum ada → otomatis register sebagai pembeli
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(8)),
                    'role' => 'pembeli',
                ]);
            }

            Auth::login($user);

            // Redirect sesuai role
            return redirect()->route('pembeli.dashboard')
                ->with('success', 'Login menggunakan Google berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
}
