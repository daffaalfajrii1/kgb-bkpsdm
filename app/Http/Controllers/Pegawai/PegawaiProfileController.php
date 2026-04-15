<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PegawaiProfileController extends Controller
{
    public function show(): View
    {
        $user = Auth::guard('pegawai')->user();

        $base = Pengajuan::query()->where('nip', $user->nip);

        $statistik = [
            'semua' => (clone $base)->count(),
            'diajukan' => (clone $base)->where('status', 'diajukan')->count(),
            'diproses' => (clone $base)->where('status', 'diproses')->count(),
            'ditolak' => (clone $base)->where('status', 'ditolak')->count(),
            'selesai' => (clone $base)->where('status', 'selesai')->count(),
        ];

        $pengajuanTerbaru = (clone $base)->latest()->take(5)->get();

        return view('pegawai.profile', compact('user', 'statistik', 'pengajuanTerbaru'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::guard('pegawai')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

