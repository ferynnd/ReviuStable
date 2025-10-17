<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {

        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));

            notify()->error("Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik ⚠️", 'Login Gagal');
            return back()->withInput()->with('lockout_seconds', $seconds);
        }

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 🔑 Coba login dengan kredensial
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // Reset percobaan gagal
            RateLimiter::clear($this->throttleKey($request));

            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                    Auth::logout();
                    notify()->error('Akun Anda tidak aktif. Hubungi administrator ⚠️', 'Login Ditolak');
                return back()->withInput();
            }

            notify()->success('Login berhasil 😊', 'Berhasil!');

            return redirect()->intended('/home');
        }

        
        RateLimiter::hit($this->throttleKey($request), 60);

        notify()->error('Username atau password salah ⚠️', 'Login Gagal');
        return back()->withInput();
    }


    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('username')).'|'.$request->ip();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        notify()->success('Kamu berhasil logout 👋', 'Sampai jumpa!');
        return redirect('/login');
    }
}