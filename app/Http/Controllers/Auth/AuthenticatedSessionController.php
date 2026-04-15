<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Kalau sedang login pegawai, jangan tampilkan halaman login admin.
        if (Auth::guard('pegawai')->check()) {
            return redirect()->route('pegawai.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Pastikan tidak bentrok dengan session pegawai.
        Auth::guard('pegawai')->logout();

        $admin = Auth::guard('web')->user();
        if (! $admin || ($admin->role ?? null) !== 'admin') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'login' => 'Akun ini tidak memiliki akses admin.',
                ])
                ->withInput($request->only('login'));
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari kedua guard jika ada
        Auth::guard('web')->logout();
        Auth::guard('pegawai')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
