<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public const SETUP_SECRET_SESSION_KEY = '2fa_setup_secret';

    public const SETUP_USER_ID_SESSION_KEY = '2fa_setup_user_id';

    public const SETUP_PENDING_SESSION_KEY = '2fa_setup_verification_pending';

    public const SETUP_VERIFICATION_SCREEN_SESSION_KEY = '2fa_setup_show_verification';

    /**
     * Tampilkan form untuk setup/verifikasi 2FA
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->google2fa_secret) {
            $this->clearSetupSession($request);

            return view('auth.2fa', ['isSetup' => false]);
        }

        if ($this->setupBelongsToUser($request)
            && $request->session()->get(self::SETUP_VERIFICATION_SCREEN_SESSION_KEY) === true) {
            return view('auth.2fa', ['isSetup' => true]);
        }

        return $this->setup($request);
    }

    /**
     * Tampilkan QR code untuk setup pertama kali
     */
    public function setup(Request $request)
    {
        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');

        if (! $this->setupBelongsToUser($request)) {
            $this->clearSetupSession($request);
            $request->session()->put([
                self::SETUP_SECRET_SESSION_KEY => $google2fa->generateSecretKey(),
                self::SETUP_USER_ID_SESSION_KEY => $user->getKey(),
            ]);
        }

        $request->session()->forget([
            self::SETUP_PENDING_SESSION_KEY,
            self::SETUP_VERIFICATION_SCREEN_SESSION_KEY,
        ]);
        $secret = $request->session()->get(self::SETUP_SECRET_SESSION_KEY);

        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.2fa-setup', [
            'qrCode' => $qrCodeUrl,
            'secret' => $secret,
        ]);
    }

    /**
     * Lanjutkan dari QR code ke form konfirmasi OTP pertama.
     */
    public function continueSetup(Request $request)
    {
        if ($request->user()->google2fa_secret) {
            return redirect()->route('2fa.index');
        }

        if (! $this->setupBelongsToUser($request)) {
            return redirect()->route('2fa.index');
        }

        $request->session()->flash(self::SETUP_VERIFICATION_SCREEN_SESSION_KEY, true);

        return redirect()->route('2fa.index');
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

        $user = $request->user();
        $google2fa = app('pragmarx.google2fa');
        $isSetup = ! $user->google2fa_secret;

        if ($isSetup) {
            if (! $this->setupBelongsToUser($request)) {
                return redirect()->route('2fa.index')
                    ->withErrors(['one_time_password' => 'Sesi setup 2FA tidak valid. Silakan scan QR code kembali.']);
            }

            $secret = $request->session()->get(self::SETUP_SECRET_SESSION_KEY);
        } else {
            $secret = $user->google2fa_secret;
        }

        $valid = $google2fa->verifyKey($secret, $request->one_time_password);

        if ($valid) {
            if ($isSetup) {
                $user->forceFill(['google2fa_secret' => $secret])->save();
                $this->clearSetupSession($request);
            }

            $request->session()->put('2fa_passed', true);

            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')->with('success', 'Verifikasi keamanan berhasil.');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Verifikasi keamanan berhasil.');
        }

        return back()->withErrors(['one_time_password' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
    }

    private function setupBelongsToUser(Request $request): bool
    {
        return $request->session()->has(self::SETUP_SECRET_SESSION_KEY)
            && (string) $request->session()->get(self::SETUP_USER_ID_SESSION_KEY)
                === (string) $request->user()->getKey();
    }

    private function clearSetupSession(Request $request): void
    {
        $request->session()->forget([
            self::SETUP_SECRET_SESSION_KEY,
            self::SETUP_USER_ID_SESSION_KEY,
            self::SETUP_PENDING_SESSION_KEY,
            self::SETUP_VERIFICATION_SCREEN_SESSION_KEY,
        ]);
    }
}
