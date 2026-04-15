<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PengajuanDiprosesMail;
use App\Mail\PengajuanDitolakMail;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\PegawaiAksesDisiplinService;

class PengajuanController extends Controller
{
    public function dashboard()
    {
        $totalPengajuan = Pengajuan::count();
        $totalDiproses = Pengajuan::where('status', 'diproses')->count();
        $totalSelesai = Pengajuan::where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'totalPengajuan',
            'totalDiproses',
            'totalSelesai'
        ));
    }

    public function index(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['diajukan', 'diproses', 'ditolak', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);

        $countSemua = Pengajuan::count();
        $countDiajukan = Pengajuan::where('status', 'diajukan')->count();
        $countDiproses = Pengajuan::where('status', 'diproses')->count();
        $countDitolak = Pengajuan::where('status', 'ditolak')->count();
        $countSelesai = Pengajuan::where('status', 'selesai')->count();

        return view('admin.pengajuan.index', compact(
            'pengajuans',
            'countSemua',
            'countDiajukan',
            'countDiproses',
            'countDitolak',
            'countSelesai'
        ));
    }

    public function diproses(Request $request)
    {
        $query = Pengajuan::where('status', 'diproses');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('admin.pengajuan.diproses', compact('pengajuans'));
    }

    public function selesaiIndex(Request $request)
    {
        $query = Pengajuan::with('hasilKgb')->where('status', 'selesai');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('dinas_instansi', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('admin.pengajuan.selesai', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('hasilKgb');

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function proses(Request $request, Pengajuan $pengajuan)
    {
        $oldStatus = $pengajuan->status;

        if ($request->boolean('verifikasi_mode')) {
            $request->validate([
                'cek_tmt_berkala_berikutnya' => ['accepted'],
                'cek_surat_pengantar_skpd' => ['accepted'],
                'cek_sk_cpns_legalisir' => ['accepted'],
                'cek_sk_pangkat_terakhir_legalisir' => ['accepted'],
                'cek_kgb_terakhir' => ['accepted'],
                'cek_skp_1_tahun_terakhir' => ['accepted'],
            ], [
                'accepted' => 'Semua checklist verifikasi wajib dicentang sebelum diproses.',
            ]);
        }

        $pengajuan->update([
            'status' => 'diproses',
            'catatan_admin' => null,
            'perbaikan_items' => null,
        ]);

        // Kirim email saat status berubah menjadi "diproses".
        if ($oldStatus !== 'diproses') {
            try {
                $nip = PegawaiAksesDisiplinService::normalizeNip($pengajuan->nip);
                $pegawai = User::query()
                    ->where('nip', $nip)
                    ->where('role', 'pegawai')
                    ->first();

                if ($pegawai && ! empty($pegawai->email)) {
                    Mail::to($pegawai->email)->send(new PengajuanDiprosesMail($pengajuan, $pegawai));
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal mengirim email pengajuan diproses', [
                    'pengajuan_id' => $pengajuan->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai diproses.');
    }

    public function selesai(Pengajuan $pengajuan)
    {
        $pengajuan->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai selesai.');
    }

    public function tolak(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'min:10', 'max:1000'],
            'perbaikan_items' => ['required', 'array', 'min:1'],
            'perbaikan_items.*' => ['string', 'in:tmt_berkala_berikutnya,surat_pengantar_skpd,sk_cpns_legalisir,sk_pangkat_terakhir_legalisir,kgb_terakhir,sk_peninjauan_masa_kerja,skp_1_tahun_terakhir'],
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => trim($validated['catatan_admin']),
            'perbaikan_items' => array_values(array_unique($validated['perbaikan_items'])),
        ]);

        // Kirim email saat pengajuan dikembalikan untuk perbaikan.
        try {
            $nip = PegawaiAksesDisiplinService::normalizeNip($pengajuan->nip);
            $pegawai = User::query()
                ->where('nip', $nip)
                ->where('role', 'pegawai')
                ->first();

            if ($pegawai && ! empty($pegawai->email)) {
                Mail::to($pegawai->email)->send(new PengajuanDitolakMail(
                    $pengajuan,
                    $pegawai,
                    is_array($pengajuan->perbaikan_items) ? $pengajuan->perbaikan_items : []
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email pengajuan ditolak', [
                'pengajuan_id' => $pengajuan->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Pengajuan ditolak dan dikembalikan ke pegawai untuk perbaikan berkas.');
    }
}