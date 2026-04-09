<?php

namespace App\Http\Middleware;

use App\Services\PegawaiAksesDisiplinService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PegawaiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('pegawai')->check()) {
            return redirect()->route('pegawai.login');
        }

        $user = Auth::guard('pegawai')->user();

        if ($user?->role !== 'pegawai') {
            abort(403);
        }

        $block = PegawaiAksesDisiplinService::pesanBlokirLogin($user);
        if ($block) {
            PegawaiAksesDisiplinService::logBlokir('middleware', $user, $block, [
                'path' => $request->path(),
            ]);

            $skpBlokir = PegawaiAksesDisiplinService::blokirLoginKarenaSkp($user);

            Auth::guard('pegawai')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $redirect = redirect()
                ->route('pegawai.login')
                ->withErrors(['nip' => $block]);

            if ($skpBlokir) {
                $redirect->with('login_blokir_skp', true)
                    ->with('pesan_blokir_login_skp', $block);
            }

            return $redirect;
        }

        return $next($request);
    }
}

