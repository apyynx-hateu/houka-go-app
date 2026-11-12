<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('petinggi')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('tutor')->check()) {
            return redirect()->route('tutor.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."]);
        }

        $credentials = $request->only('username', 'password');

        // 1. Coba login sebagai Petinggi (ADMIN)
        if (Auth::guard('petinggi')->attempt($credentials)) {
            $petinggi = Auth::guard('petinggi')->user();

            if (!$petinggi->isAktif()) {
                Auth::guard('petinggi')->logout();
                RateLimiter::hit($throttleKey);
                return back()
                    ->withInput($request->only('username'))
                    ->withErrors(['username' => 'Akun Admin Anda tidak aktif. Hubungi administrator.']);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. Coba login sebagai Tutor
        if (Auth::guard('tutor')->attempt($credentials)) {
            $tutor = Auth::guard('tutor')->user();

            if (!$tutor->isAktif()) {
                Auth::guard('tutor')->logout();
                RateLimiter::hit($throttleKey);
                return back()
                    ->withInput($request->only('username'))
                    ->withErrors(['username' => 'Akun Tutor Anda tidak aktif.']);
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('tutor.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('petinggi')->logout();
        Auth::guard('tutor')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
