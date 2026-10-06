<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (session('is_guest')) {
            return redirect()->route('diagnosa.form');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Hapus mode guest jika sebelumnya aktif
            $request->session()->forget(['is_guest', 'guest_name']);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Enable guest mode access for testing diagnosis without login.
     */
    public function guestAccess(Request $request)
    {
        session([
            'is_guest' => true,
            'guest_name' => 'Pengguna Tamu'
        ]);

        return redirect()->route('diagnosa.form')
            ->with('success', 'Anda masuk sebagai Tamu. Anda dapat melakukan pengujian diagnosa dengan akses terbatas.');
    }

    /**
     * Exit guest mode and return to login page.
     */
    public function guestLogout(Request $request)
    {
        $request->session()->forget(['is_guest', 'guest_name']);

        return redirect()->route('login')->with('success', 'Anda telah keluar dari mode Tamu.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
