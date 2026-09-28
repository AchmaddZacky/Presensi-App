<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pegawai.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->with('error', 'Username atau password salah. Silakan coba lagi.')
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $nama = $user->pegawai->nama ?? $user->username;

        return $user->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))->with('success', "Selamat datang kembali, {$nama}! Anda berhasil login.")
            : redirect()->intended(route('pegawai.dashboard'))->with('success', "Selamat datang kembali, {$nama}! Anda berhasil login.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari sistem.');
    }
}
