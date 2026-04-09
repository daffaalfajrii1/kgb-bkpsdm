<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PegawaiAksesDisiplinService;
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

        $nip = PegawaiAksesDisiplinService::normalizeNip((string) $credentials['nip']);

        if ($nip !== '') {
            $user = User::query()->where('nip', $nip)->first();

            if ($user) {
                $block = PegawaiAksesDisiplinService::pesanBlokirLogin($user);
                if ($block) {
                    PegawaiAksesDisiplinService::logBlokir('login', $user, $block, ['via' => 'pegawai_login']);

                    $redirect = back()
                        ->withErrors(['nip' => $block])
                        ->withInput($request->only('nip'));

                    if (PegawaiAksesDisiplinService::blokirLoginKarenaSkp($user)) {
                        $redirect->with('login_blokir_skp', true)
                            ->with('pesan_blokir_login_skp', $block);
                    }

                    return $redirect;
                }
            }
        }

        if (! Auth::guard('pegawai')->attempt([
            'nip' => $nip !== '' ? $nip : $credentials['nip'],
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

