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
        return ['Baik', 'Buruk', 'Sangat Buruk'];
    }

    /**
     * Satu nilai predikat untuk form; sertakan nilai lama di DB jika di luar daftar standar.
     *
     * @return list<string>
     */
    private function predikatSkpPilihanUntukEdit(PegawaiSkpDuaTahun $skp): array
    {
        $base = $this->predikatSkpPilihan();
        foreach ([$skp->predikat_terbaru, $skp->predikat_sebelumnya] as $v) {
            $v = trim((string) $v);
            if ($v !== '' && ! in_array($v, $base, true)) {
                $base[] = $v;
            }
        }

        return array_values(array_unique($base));
    }

    /**
     * Nilai awal satu predikat untuk form edit (data lama bisa beda per tahun).
     */
    private function predikatGabungUntukFormEdit(PegawaiSkpDuaTahun $skp): string
    {
        $a = trim((string) $skp->predikat_terbaru);
        $b = trim((string) $skp->predikat_sebelumnya);

        return $a === $b ? $a : $a;
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

        [$tAutoBaru, $tAutoLama] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $items = $query->orderByDesc('tahun_terbaru')->paginate(20)->withQueryString();

        return view('admin.skp.index', compact('items', 'tAutoBaru', 'tAutoLama'));
    }

    public function create(): View
    {
        $this->authorize('create', PegawaiSkpDuaTahun::class);

        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        [$tahunOtomatisBaru, $tahunOtomatisLama] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikatOpsi = $this->predikatSkpPilihan();

        return view('admin.skp.create', compact(
            'pegawais',
            'tahunOtomatisBaru',
            'tahunOtomatisLama',
            'predikatOpsi'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', PegawaiSkpDuaTahun::class);

        $predikatList = $this->predikatSkpPilihan();

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'pegawai')],
            'predikat_2_tahun' => ['required', 'string', Rule::in($predikatList)],
        ]);

        [$tahunTerbaru, $tahunSebelumnya] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikat = $validated['predikat_2_tahun'];

        PegawaiSkpDuaTahun::query()->updateOrCreate(
            ['user_id' => $validated['user_id']],
            [
                'tahun_terbaru' => $tahunTerbaru,
                'predikat_terbaru' => $predikat,
                'tahun_sebelumnya' => $tahunSebelumnya,
                'predikat_sebelumnya' => $predikat,
            ]
        );

        return redirect()
            ->route('admin.pegawai-skp.index')
            ->with('success', 'Data SKP 2 tahun terakhir berhasil disimpan.');
    }

    public function edit(PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): View
    {
        $this->authorize('update', $pegawaiSkpDuaTahun);

        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        [$tahunOtomatisBaru, $tahunOtomatisLama] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikatOpsi = $this->predikatSkpPilihanUntukEdit($pegawaiSkpDuaTahun);
        $predikatDefaultForm = $this->predikatGabungUntukFormEdit($pegawaiSkpDuaTahun);
        $predikatPerTahunBerbeda = trim((string) $pegawaiSkpDuaTahun->predikat_terbaru)
            !== trim((string) $pegawaiSkpDuaTahun->predikat_sebelumnya);
        $periodeTersimpanBerbeda = (int) $pegawaiSkpDuaTahun->tahun_terbaru !== $tahunOtomatisBaru
            || (int) $pegawaiSkpDuaTahun->tahun_sebelumnya !== $tahunOtomatisLama;

        return view('admin.skp.edit', [
            'skp' => $pegawaiSkpDuaTahun,
            'pegawais' => $pegawais,
            'tahunOtomatisBaru' => $tahunOtomatisBaru,
            'tahunOtomatisLama' => $tahunOtomatisLama,
            'predikatOpsi' => $predikatOpsi,
            'predikatDefaultForm' => $predikatDefaultForm,
            'predikatPerTahunBerbeda' => $predikatPerTahunBerbeda,
            'periodeTersimpanBerbeda' => $periodeTersimpanBerbeda,
        ]);
    }

    public function update(Request $request, PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): RedirectResponse
    {
        $this->authorize('update', $pegawaiSkpDuaTahun);

        $predikatList = $this->predikatSkpPilihanUntukEdit($pegawaiSkpDuaTahun);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'pegawai')],
            'predikat_2_tahun' => ['required', 'string', Rule::in($predikatList)],
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

        [$tahunTerbaru, $tahunSebelumnya] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();
        $predikat = $validated['predikat_2_tahun'];

        $pegawaiSkpDuaTahun->update([
            'user_id' => $validated['user_id'],
            'tahun_terbaru' => $tahunTerbaru,
            'predikat_terbaru' => $predikat,
            'tahun_sebelumnya' => $tahunSebelumnya,
            'predikat_sebelumnya' => $predikat,
        ]);

        return redirect()
            ->route('admin.pegawai-skp.index')
            ->with('success', 'Data SKP 2 tahun terakhir berhasil diperbarui.');
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
