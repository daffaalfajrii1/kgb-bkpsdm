<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Services\PegawaiAksesDisiplinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PegawaiPengajuanController extends Controller
{
    private array $uploadFields = [
        'surat_pengantar_skpd',
        'sk_cpns_legalisir',
        'sk_pangkat_terakhir_legalisir',
        'kgb_terakhir',
        'sk_peninjauan_masa_kerja',
        'skp_1_tahun_terakhir',
    ];

    public function index(): View
    {
        $user = Auth::guard('pegawai')->user();

        $pengajuans = Pengajuan::with('hasilKgb')
            ->where('nip', $user->nip)
            ->latest()
            ->paginate(10);

        $bolehAjukan = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user) === null;

        return view('pegawai.pengajuan.index', compact('pengajuans', 'user', 'bolehAjukan'));
    }

    public function create(): View|RedirectResponse
    {
        $user = Auth::guard('pegawai')->user();

        if ($msg = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user)) {
            PegawaiAksesDisiplinService::logBlokir('pengajuan', $user, $msg, ['action' => 'create']);

            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => $msg]);
        }

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

        if ($msg = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user)) {
            PegawaiAksesDisiplinService::logBlokir('pengajuan', $user, $msg, ['action' => 'store']);

            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => $msg]);
        }

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

    public function edit(Pengajuan $pengajuan): View|RedirectResponse
    {
        $user = Auth::guard('pegawai')->user();

        if ($pengajuan->nip !== $user->nip) {
            abort(403);
        }

        if ($pengajuan->status !== 'ditolak') {
            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => 'Hanya pengajuan berstatus ditolak yang dapat diperbaiki.']);
        }

        if ($msg = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user)) {
            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => $msg]);
        }

        $requiredFixes = $pengajuan->perbaikan_items;
        if (! is_array($requiredFixes)) {
            $requiredFixes = [];
        }

        return view('pegawai.pengajuan.edit', compact('user', 'pengajuan', 'requiredFixes'));
    }

    public function update(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $user = Auth::guard('pegawai')->user();

        if ($pengajuan->nip !== $user->nip) {
            abort(403);
        }

        if ($pengajuan->status !== 'ditolak') {
            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => 'Hanya pengajuan berstatus ditolak yang dapat diperbaiki.']);
        }

        if ($msg = PegawaiAksesDisiplinService::pesanBlokirPengajuan($user)) {
            return redirect()
                ->route('pegawai.pengajuan.index')
                ->withErrors(['pengajuan' => $msg]);
        }

        $requiredFixes = $pengajuan->perbaikan_items;
        if (! is_array($requiredFixes)) {
            $requiredFixes = [];
        }

        $rules = [
            'tmt_berkala_berikutnya' => in_array('tmt_berkala_berikutnya', $requiredFixes, true) ? 'required|date' : 'nullable|date',
            'surat_pengantar_skpd' => in_array('surat_pengantar_skpd', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
            'sk_cpns_legalisir' => in_array('sk_cpns_legalisir', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
            'sk_pangkat_terakhir_legalisir' => in_array('sk_pangkat_terakhir_legalisir', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
            'kgb_terakhir' => in_array('kgb_terakhir', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
            'sk_peninjauan_masa_kerja' => in_array('sk_peninjauan_masa_kerja', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
            'skp_1_tahun_terakhir' => in_array('skp_1_tahun_terakhir', $requiredFixes, true) ? 'required|mimes:pdf|max:2048' : 'nullable|mimes:pdf|max:2048',
        ];
        $validated = $request->validate($rules);

        $adaPerubahanBerkas = false;
        foreach ($this->uploadFields as $field) {
            if ($request->hasFile($field)) {
                $adaPerubahanBerkas = true;
                break;
            }
        }

        if (! $adaPerubahanBerkas && $requiredFixes === []) {
            return back()->withErrors([
                'pengajuan' => 'Unggah minimal 1 berkas yang diperbaiki sebelum kirim ulang.',
            ]);
        }

        $dataUpdate = [
            'tmt_berkala_berikutnya' => $validated['tmt_berkala_berikutnya'] ?? $pengajuan->tmt_berkala_berikutnya,
            'status' => 'diajukan',
            'catatan_admin' => null,
            'perbaikan_items' => null,
        ];

        foreach ($this->uploadFields as $field) {
            if ($request->hasFile($field)) {
                if (! empty($pengajuan->{$field})) {
                    Storage::disk('public')->delete($pengajuan->{$field});
                }
                $dataUpdate[$field] = $request->file($field)->store('pengajuan', 'public');
            }
        }

        $pengajuan->update($dataUpdate);

        return redirect()
            ->route('pegawai.pengajuan.index')
            ->with('success', 'Perbaikan pengajuan berhasil dikirim ulang. Status kembali menjadi diajukan.');
    }
}

