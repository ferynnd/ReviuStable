<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
// Forgot Password
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view("login");
    }

    public function login(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            return back()->withInput()->with('error', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik ⚠️")->with("lockout_seconds", $seconds);
        }

        $credentials = $request->validate([
            "username" => ["required", "string"],
            "password" => ["required", "string"],
        ]);

        if (
            Auth::attempt([
                "username" => $request->username,
                "password" => $request->password,
            ])
        ) {
            // Reset percobaan gagal
            RateLimiter::clear($this->throttleKey($request));

            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withInput()->with('error', 'Akun Anda tidak aktif. Hubungi administrator ⚠️');
            }

            return redirect()->intended("/")->with('success', 'Login berhasil 😊');
        }

        RateLimiter::hit($this->throttleKey($request), 60);

        return back()->withInput()->with('error', 'Username atau password salah ⚠️');
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input("username")) . "|" . $request->ip();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/login")->with('success', 'Kamu berhasil logout 👋');
    }


    // Menampilkan form forgot password
    public function showForgot()
    {
        return view('auth.forgot');
    }

    // Mengirim email reset password
    public function storeForgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showReset(Request $request, $token)
    {
        return view('auth.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function storeReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login kembali.');
        }

        return back()->with('error', __($status));
    }



}
