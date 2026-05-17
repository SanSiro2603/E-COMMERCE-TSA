<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
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
            try {
                $googleUser = Socialite::driver('google')->user();
            } catch (InvalidStateException $e) {
                // Fallback ketika state session hilang/mismatch (kasus umum localhost/domain beda)
                Log::warning('Google login invalid state, retrying stateless', [
                    'request_url' => request()->fullUrl(),
                    'request_host' => request()->getHost(),
                    'session_id' => request()->session()->getId(),
                    'query_state' => request()->query('state'),
                    'session_state' => request()->session()->get('state'),
                ]);

                $googleUser = Socialite::driver('google')->stateless()->user();
            }

            // Cek apakah user sudah terdaftar
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user && $user->role !== 'pembeli') {
                return redirect()->route('login')
                    ->with('error', 'Anda harus login melalui username password dan mengisi captcha.');
            }

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
            Log::error('Google login callback failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'request_url' => request()->fullUrl(),
                'request_host' => request()->getHost(),
                'session_id' => request()->session()->getId(),
                'query_state' => request()->query('state'),
                'session_state' => request()->session()->get('state'),
            ]);

            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
}
