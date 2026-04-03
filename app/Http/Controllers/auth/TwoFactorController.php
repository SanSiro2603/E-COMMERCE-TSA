<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFactorController extends Controller
{
    /**
     * Tampilkan form untuk setup/verifikasi 2FA
     */
    public function index()
    {
        $user = Auth::user();

        // Jika user belum punya secret 2FA, arahkan ke setup
        if (!$user->google2fa_secret) {
            return $this->setup();
        }

        // Jika sudah punya, tampilkan form verifikasi
        return view('auth.2fa');
    }

    /**
     * Tampilkan QR code untuk setup pertama kali
     */
    public function setup()
    {
        $user = Auth::user();

        // Generate secret baru jika belum ada
        if (!$user->google2fa_secret) {
            $google2fa = app('pragmarx.google2fa');
            $secret = $google2fa->generateSecretKey();
            
            $user->google2fa_secret = $secret;
            $user->save();
        }

        $google2fa = app('pragmarx.google2fa');
        
        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa-setup', [
            'qrCode' => $qrCodeUrl,
            'secret' => $user->google2fa_secret
        ]);
    }

    /**
     * Verifikasi kode OTP (untuk setup maupun login reguler)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ], [
            'one_time_password.required' => 'Kode OTP wajib diisi.',
            'one_time_password.digits' => 'Kode OTP harus 6 angka.',
        ]);

        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // Tandai session bahwa 2fa sudah sukses
            $request->session()->put('2fa_passed', true);

            // Redirect ke dashboard masing-masing
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')->with('success', 'Verifikasi keamanan berhasil.');
            }
            return redirect()->route('admin.dashboard')->with('success', 'Verifikasi keamanan berhasil.');
        }

        return back()->withErrors(['one_time_password' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
    }
}
