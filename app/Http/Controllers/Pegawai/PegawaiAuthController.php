<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\DisiplinPegawai;
use App\Models\User;
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

        $nip = preg_replace('/\s+/', '', (string) $credentials['nip']);
        $nip = ltrim($nip, "'’`");
        $nip = preg_replace('/[^0-9]/', '', $nip) ?? '';

        if ($nip !== '') {
            $user = User::query()->where('nip', $nip)->first();

            if ($user) {
                $activeDisiplin = DisiplinPegawai::query()
                    ->where('user_id', $user->id)
                    ->where('selesai', false)
                    ->whereIn('tingkat_hukuman', ['sedang', 'berat'])
                    ->whereDate('tmt_berlaku', '<=', now()->toDateString())
                        ->where(function ($q) {
                            $q->whereNull('tmt_selesai')
                                ->orWhere('tmt_selesai', '>=', now()->toDateString());
                        })
                    ->latest('id')
                    ->first();

                if ($activeDisiplin) {
                    $tingkat = $activeDisiplin->tingkat_hukuman;
                    $tmt = $activeDisiplin->tmt_berlaku?->format('d/m/Y');
                    $hukuman = (string) $activeDisiplin->hukuman_disiplin;

                    return back()
                        ->withErrors([
                            'nip' => "Login ditolak: pegawai sedang dalam hukuman disiplin {$tingkat}. (TMT berlaku: {$tmt}). Hukuman: ".mb_substr($hukuman, 0, 120),
                        ])
                        ->withInput($request->only('nip'));
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

