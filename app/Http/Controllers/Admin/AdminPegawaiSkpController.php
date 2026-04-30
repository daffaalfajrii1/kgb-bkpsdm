<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PegawaiSkpDuaTahun;
use App\Models\User;
use App\Services\PegawaiAksesDisiplinService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPegawaiSkpController extends Controller
{
    use AuthorizesRequests;

    /** @return list<string> */
    private function predikatSkpPilihan(): array
    {
        return ['Butuh Perbaikan', 'Kurang', 'Sangat kurang', 'Tidak ada Predikat'];
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PegawaiSkpDuaTahun::class);

        $query = PegawaiSkpDuaTahun::query()->with('user');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        }

        [$tAutoBaru] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $items = $query->orderByDesc('tahun_terbaru')->paginate(20)->withQueryString();

        return view('admin.skp.index', compact('items', 'tAutoBaru'));
    }

    public function create(): View
    {
        $this->authorize('create', PegawaiSkpDuaTahun::class);

        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        [$tahunOtomatisBaru] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikatOpsi = $this->predikatSkpPilihan();

        return view('admin.skp.create', compact(
            'pegawais',
            'tahunOtomatisBaru',
            'predikatOpsi'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', PegawaiSkpDuaTahun::class);

        $predikatList = $this->predikatSkpPilihan();

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'pegawai')],
            'predikat_1_tahun' => ['required', 'string', Rule::in($predikatList)],
        ]);

        [$tahunTerbaru] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikat = $validated['predikat_1_tahun'];

        PegawaiSkpDuaTahun::query()->updateOrCreate(
            ['user_id' => $validated['user_id']],
            [
                'tahun_terbaru' => $tahunTerbaru,
                'predikat_terbaru' => $predikat,
                'tahun_sebelumnya' => $tahunTerbaru,
                'predikat_sebelumnya' => $predikat,
            ]
        );

        return redirect()
            ->route('admin.pegawai-skp.index')
            ->with('success', 'Data SKP 1 tahun terakhir berhasil disimpan.');
    }

    public function edit(PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): View
    {
        $this->authorize('update', $pegawaiSkpDuaTahun);

        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        [$tahunOtomatisBaru] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikatOpsi = $this->predikatSkpPilihan();

        return view('admin.skp.edit', [
            'skp' => $pegawaiSkpDuaTahun,
            'pegawais' => $pegawais,
            'tahunOtomatisBaru' => $tahunOtomatisBaru,
            'predikatOpsi' => $predikatOpsi,
        ]);
    }

    public function update(Request $request, PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): RedirectResponse
    {
        $this->authorize('update', $pegawaiSkpDuaTahun);

        $predikatList = $this->predikatSkpPilihan();

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'pegawai')],
            'predikat_1_tahun' => ['required', 'string', Rule::in($predikatList)],
        ]);

        if ((int) $validated['user_id'] !== (int) $pegawaiSkpDuaTahun->user_id) {
            $exists = PegawaiSkpDuaTahun::query()
                ->where('user_id', $validated['user_id'])
                ->whereKeyNot($pegawaiSkpDuaTahun->getKey())
                ->exists();
            if ($exists) {
                throw ValidationException::withMessages([
                    'user_id' => 'Pegawai ini sudah memiliki data SKP. Hapus atau ubah data yang ada terlebih dahulu.',
                ]);
            }
        }

        [$tahunTerbaru] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikat = $validated['predikat_1_tahun'];

        $pegawaiSkpDuaTahun->update([
            'user_id' => $validated['user_id'],
            'tahun_terbaru' => $tahunTerbaru,
            'predikat_terbaru' => $predikat,
            'tahun_sebelumnya' => $tahunTerbaru,
            'predikat_sebelumnya' => $predikat,
        ]);

        return redirect()
            ->route('admin.pegawai-skp.index')
            ->with('success', 'Data SKP 1 tahun terakhir berhasil diperbarui.');
    }

    public function destroy(PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): RedirectResponse
    {
        $this->authorize('delete', $pegawaiSkpDuaTahun);

        $pegawaiSkpDuaTahun->delete();

        return redirect()
            ->route('admin.pegawai-skp.index')
            ->with('success', 'Data SKP pegawai berhasil dihapus.');
    }
}
