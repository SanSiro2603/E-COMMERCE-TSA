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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect berdasarkan role
            return redirect()->intended($this->redirectTo($user->role));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }

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