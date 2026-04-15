<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilKgb;
use App\Models\Pengajuan;
use App\Services\PegawaiAksesDisiplinService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PegawaiDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('pegawai')->user();

        $baseQuery = Pengajuan::query()->where('nip', $user->nip);

        $stats = [
            'total_pengajuan' => (clone $baseQuery)->count(),
            'diajukan' => (clone $baseQuery)->where('status', 'diajukan')->count(),
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'ditolak' => (clone $baseQuery)->where('status', 'ditolak')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'sk_terbit' => HasilKgb::query()->where('nip', $user->nip)->count(),
        ];

        $pengajuanTerbaru = (clone $baseQuery)->latest()->take(5)->get();
        $pengajuanTerakhir = (clone $baseQuery)->latest()->first();
        $bolehAjukan = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user) === null;
        $pesanBlokir = $bolehAjukan ? null : PegawaiAksesDisiplinService::pesanBlokirPengajuan($user);

        return view('pegawai.dashboard', compact(
            'user',
            'stats',
            'pengajuanTerbaru',
            'pengajuanTerakhir',
            'bolehAjukan',
            'pesanBlokir'
        ));
    }
}

