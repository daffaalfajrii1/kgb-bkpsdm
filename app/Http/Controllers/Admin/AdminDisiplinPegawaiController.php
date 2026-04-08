<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisiplinPegawai;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDisiplinPegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $query = DisiplinPegawai::query()->with('user');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        }

        if ($request->filled('tingkat')) {
            $tingkat = $request->string('tingkat')->toString();
            $query->where('tingkat_hukuman', $tingkat);
        }

        $query->orderByDesc('tmt_berlaku');

        $items = $query->paginate(20)->withQueryString();

        return view('admin.disiplin.index', compact('items'));
    }

    public function create(): View
    {
        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        return view('admin.disiplin.create', compact('pegawais'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pegawai_id' => ['required', 'integer', 'exists:users,id'],
            'tmt_berlaku' => ['required', 'date'],
            'tmt_selesai' => ['nullable', 'date'],
            'selesai' => ['nullable', 'boolean'],
            'tingkat_hukuman' => ['required', Rule::in(['ringan', 'sedang', 'berat'])],
            'hukuman_disiplin' => ['required', 'string', 'max:1000'],
        ]);

        DisiplinPegawai::create([
            'user_id' => $validated['pegawai_id'],
            'tmt_berlaku' => $validated['tmt_berlaku'],
            'tmt_selesai' => $validated['tmt_selesai'] ?? null,
            'selesai' => $validated['selesai'] ?? false,
            'tingkat_hukuman' => $validated['tingkat_hukuman'],
            'hukuman_disiplin' => $validated['hukuman_disiplin'],
        ]);

        return redirect()
            ->route('admin.disiplin.index')
            ->with('success', 'Data hukuman disiplin pegawai berhasil ditambahkan.');
    }

    public function edit(DisiplinPegawai $disiplin): View
    {
        $pegawais = User::query()
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get(['id', 'nip', 'name']);

        return view('admin.disiplin.edit', [
            'disiplin' => $disiplin,
            'pegawais' => $pegawais,
        ]);
    }

    public function update(Request $request, DisiplinPegawai $disiplin): RedirectResponse
    {
        $validated = $request->validate([
            'pegawai_id' => ['required', 'integer', 'exists:users,id'],
            'tmt_berlaku' => ['required', 'date'],
            'tmt_selesai' => ['nullable', 'date'],
            'selesai' => ['nullable', 'boolean'],
            'tingkat_hukuman' => ['required', Rule::in(['ringan', 'sedang', 'berat'])],
            'hukuman_disiplin' => ['required', 'string', 'max:1000'],
        ]);

        $disiplin->update([
            'user_id' => $validated['pegawai_id'],
            'tmt_berlaku' => $validated['tmt_berlaku'],
            'tmt_selesai' => $validated['tmt_selesai'] ?? null,
            'selesai' => $validated['selesai'] ?? false,
            'tingkat_hukuman' => $validated['tingkat_hukuman'],
            'hukuman_disiplin' => $validated['hukuman_disiplin'],
        ]);

        return redirect()
            ->route('admin.disiplin.index')
            ->with('success', 'Data hukuman disiplin pegawai berhasil diperbarui.');
    }

    public function destroy(DisiplinPegawai $disiplin): RedirectResponse
    {
        $disiplin->delete();

        return redirect()
            ->route('admin.disiplin.index')
            ->with('success', 'Data hukuman disiplin pegawai berhasil dihapus.');
    }
}

