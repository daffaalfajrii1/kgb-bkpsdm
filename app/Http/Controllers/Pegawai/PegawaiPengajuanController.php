<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PegawaiPengajuanController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('pegawai')->user();

        $pengajuans = Pengajuan::with('hasilKgb')
            ->where('nip', $user->nip)
            ->latest()
            ->paginate(10);

        return view('pegawai.pengajuan.index', compact('pengajuans', 'user'));
    }

    public function create(): View
    {
        $user = Auth::guard('pegawai')->user();

        $pangkatOptions = [
            'I/a','I/b','I/c','I/d',
            'II/a','II/b','II/c','II/d',
            'III/a','III/b','III/c','III/d',
            'IV/a','IV/b','IV/c','IV/d','IV/e',
        ];

        return view('pegawai.pengajuan.create', compact('user', 'pangkatOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::guard('pegawai')->user();

        $validated = $request->validate([
            'tmt_berkala_berikutnya' => 'required|date',
            'surat_pengantar_skpd' => 'required|mimes:pdf|max:2048',
            'sk_cpns_legalisir' => 'required|mimes:pdf|max:2048',
            'sk_pangkat_terakhir_legalisir' => 'required|mimes:pdf|max:2048',
            'kgb_terakhir' => 'required|mimes:pdf|max:2048',
            'sk_peninjauan_masa_kerja' => 'nullable|mimes:pdf|max:2048',
            'skp_1_tahun_terakhir' => 'required|mimes:pdf|max:2048',
        ]);

        $data = [
            'nama' => $user->name,
            'nip' => $user->nip,
            'dinas_instansi' => $user->dinas_instansi,
            'pangkat_terakhir' => $user->gol_pangkat ?? $user->pangkat_terakhir,
            'tmt_berkala_berikutnya' => $validated['tmt_berkala_berikutnya'],
            'status' => 'diajukan',
            'surat_pengantar_skpd' => $request->file('surat_pengantar_skpd')->store('pengajuan', 'public'),
            'sk_cpns_legalisir' => $request->file('sk_cpns_legalisir')->store('pengajuan', 'public'),
            'sk_pangkat_terakhir_legalisir' => $request->file('sk_pangkat_terakhir_legalisir')->store('pengajuan', 'public'),
            'kgb_terakhir' => $request->file('kgb_terakhir')->store('pengajuan', 'public'),
            'skp_1_tahun_terakhir' => $request->file('skp_1_tahun_terakhir')->store('pengajuan', 'public'),
        ];

        if ($request->hasFile('sk_peninjauan_masa_kerja')) {
            $data['sk_peninjauan_masa_kerja'] = $request->file('sk_peninjauan_masa_kerja')->store('pengajuan', 'public');
        }

        Pengajuan::create($data);

        return redirect()
            ->route('pegawai.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim. Silakan pantau status pengajuan Anda di halaman ini.');
    }
}

