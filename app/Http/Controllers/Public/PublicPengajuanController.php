<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PublicPengajuanController extends Controller
{
    public function create()
    {
        $pangkatOptions = [
            'I/a','I/b','I/c','I/d',
            'II/a','II/b','II/c','II/d',
            'III/a','III/b','III/c','III/d',
            'IV/a','IV/b','IV/c','IV/d','IV/e',
        ];

        return view('public.pengajuan.create', compact('pangkatOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'dinas_instansi' => 'required|string|max:255',
            'pangkat_terakhir' => 'required|string|max:20',
            'tmt_berkala_berikutnya' => 'required|date',

            'surat_pengantar_skpd' => 'required|mimes:pdf|max:2048',
            'sk_cpns_legalisir' => 'required|mimes:pdf|max:2048',
            'sk_pangkat_terakhir_legalisir' => 'required|mimes:pdf|max:2048',
            'kgb_terakhir' => 'required|mimes:pdf|max:2048',
            'sk_peninjauan_masa_kerja' => 'nullable|mimes:pdf|max:2048',
            'skp_1_tahun_terakhir' => 'required|mimes:pdf|max:2048',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'dinas_instansi' => $validated['dinas_instansi'],
            'pangkat_terakhir' => $validated['pangkat_terakhir'],
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

        $pengajuan = Pengajuan::create($data);

        return redirect()
            ->route('public.status.index', ['nomor_registrasi' => $pengajuan->nomor_registrasi])
            ->with('success', 'Pengajuan berhasil dikirim. Simpan nomor registrasi Anda: '.$pengajuan->nomor_registrasi);
    }
}