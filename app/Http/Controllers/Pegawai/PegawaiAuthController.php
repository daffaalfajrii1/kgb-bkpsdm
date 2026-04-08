<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PegawaiAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('pegawai.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('pegawai')->attempt([
            'nip' => $credentials['nip'],
            'password' => $credentials['password'],
        ], $remember)) {
            return back()
                ->withErrors([
                    'nip' => 'NIP atau password tidak sesuai.',
                ])
                ->withInput($request->only('nip'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('pegawai.dashboard', absolute: false));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('pegawai')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

