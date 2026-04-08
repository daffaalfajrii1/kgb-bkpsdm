<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PegawaiExcelImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPegawaiController extends Controller
{
    private const PANGKAT_ORDER_DESC = [
        'IV/e', 'IV/d', 'IV/c', 'IV/b', 'IV/a',
        'III/d', 'III/c', 'III/b', 'III/a',
        'II/d', 'II/c', 'II/b', 'II/a',
        'I/d', 'I/c', 'I/b', 'I/a',
    ];

    public function index(Request $request): View
    {
        $query = User::query()->where('role', 'pegawai');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('instansi')) {
            $query->where('dinas_instansi', $request->string('instansi')->toString());
        }

        $sort = $request->string('sort')->toString();
        if ($sort === 'nip') {
            $query->orderBy('nip');
        } elseif ($sort === 'nama') {
            $query->orderBy('name');
        } elseif ($sort === 'pangkat_desc') {
            // Urutkan pangkat tertinggi → terendah (IV/e ... I/a), lalu nama.
            $expr = "CASE COALESCE(NULLIF(gol_pangkat,''), NULLIF(pangkat_terakhir,''))\n";
            foreach (self::PANGKAT_ORDER_DESC as $i => $pangkat) {
                $rank = $i + 1;
                $expr .= " WHEN '{$pangkat}' THEN {$rank}\n";
            }
            $expr .= " ELSE 999 END";
            $query->orderByRaw($expr)->orderBy('name');
        } else {
            $query->orderBy('dinas_instansi')->orderBy('name');
        }

        $pegawais = $query->paginate(25)->withQueryString();

        $instansiList = User::query()
            ->where('role', 'pegawai')
            ->whereNotNull('dinas_instansi')
            ->where('dinas_instansi', '!=', '')
            ->distinct()
            ->orderBy('dinas_instansi')
            ->pluck('dinas_instansi');

        return view('admin.pegawai.index', compact('pegawais', 'instansiList'));
    }

    public function create(): View
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'gol_pangkat' => ['nullable', 'string', 'max:100'],
            'tmt_golongan' => ['nullable', 'date'],
            'mk_tahun' => ['nullable', 'integer', 'min:0', 'max:80'],
            'mk_bulan' => ['nullable', 'integer', 'min:0', 'max:11'],
            'tmt_jabatan' => ['nullable', 'date'],
            'dinas_instansi' => ['nullable', 'string', 'max:255'],
        ]);

        $nip = preg_replace('/\s+/', '', $validated['nip']);

        User::create([
            'nip' => $nip,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'pegawai',
            'password' => $nip,
            'gol_pangkat' => $validated['gol_pangkat'] ?? null,
            'pangkat_terakhir' => $validated['gol_pangkat'] ?? null,
            'tmt_golongan' => $validated['tmt_golongan'] ?? null,
            'mk_tahun' => $validated['mk_tahun'] ?? null,
            'mk_bulan' => $validated['mk_bulan'] ?? null,
            'tmt_jabatan' => $validated['tmt_jabatan'] ?? null,
            'dinas_instansi' => $validated['dinas_instansi'] ?? null,
        ]);

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan. Password default sama dengan NIP.');
    }

    public function edit(int $pegawai): View
    {
        $user = User::query()->where('role', 'pegawai')->findOrFail($pegawai);

        return view('admin.pegawai.edit', compact('user'));
    }

    public function update(Request $request, int $pegawai): RedirectResponse
    {
        $user = User::query()->where('role', 'pegawai')->findOrFail($pegawai);

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip,'.$user->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'gol_pangkat' => ['nullable', 'string', 'max:100'],
            'tmt_golongan' => ['nullable', 'date'],
            'mk_tahun' => ['nullable', 'integer', 'min:0', 'max:80'],
            'mk_bulan' => ['nullable', 'integer', 'min:0', 'max:11'],
            'tmt_jabatan' => ['nullable', 'date'],
            'dinas_instansi' => ['nullable', 'string', 'max:255'],
            'reset_password_nip' => ['nullable', 'boolean'],
        ]);

        $nip = preg_replace('/\s+/', '', $validated['nip']);

        $user->fill([
            'nip' => $nip,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gol_pangkat' => $validated['gol_pangkat'] ?? null,
            'pangkat_terakhir' => $validated['gol_pangkat'] ?? null,
            'tmt_golongan' => $validated['tmt_golongan'] ?? null,
            'mk_tahun' => $validated['mk_tahun'] ?? null,
            'mk_bulan' => $validated['mk_bulan'] ?? null,
            'tmt_jabatan' => $validated['tmt_jabatan'] ?? null,
            'dinas_instansi' => $validated['dinas_instansi'] ?? null,
        ]);

        if ($request->boolean('reset_password_nip')) {
            $user->password = $nip;
        }

        $user->save();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(int $pegawai): RedirectResponse
    {
        $user = User::query()->where('role', 'pegawai')->findOrFail($pegawai);
        $user->delete();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    public function resetPassword(int $pegawai): RedirectResponse
    {
        $user = User::query()->where('role', 'pegawai')->findOrFail($pegawai);

        $nip = preg_replace('/\s+/', '', (string) $user->nip);
        $nip = ltrim($nip, "'’`");
        $nip = preg_replace('/[^0-9]/', '', $nip) ?? '';

        if ($nip === '') {
            return back()->withErrors(['reset' => 'Reset password gagal: NIP kosong / tidak valid.']);
        }

        // Password default = NIP (akan otomatis di-hash oleh cast "hashed")
        $user->password = $nip;
        $user->save();

        return back()->with('success', 'Password pegawai berhasil direset menjadi NIP.');
    }

    public function import(Request $request, PegawaiExcelImporter $importer): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:30720'],
        ]);

        $path = $request->file('file')->getRealPath();

        try {
            $result = $importer->import($path);
        } catch (Throwable $e) {
            Log::error('Impor pegawai gagal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('admin.pegawai.index')
                ->withErrors([
                    'impor' => 'Impor gagal: '.$e->getMessage().' (cek juga storage/logs/laravel.log dan batas PHP di php.ini).',
                ]);
        }

        $msg = "Impor selesai: {$result['imported']} baru, {$result['updated']} diperbarui, {$result['skipped']} dilewati.";
        if ($result['errors'] !== []) {
            $msg .= ' Detail: '.implode(' ', array_slice($result['errors'], 0, 10));
            if (count($result['errors']) > 10) {
                $msg .= ' ...';
            }
        }

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', $msg);
    }

    public function template(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'NIP',
            'NAMA',
            'EMAIL',
            'GOL./PANGKAT',
            'TMT GOLONGAN',
            'MK TAHUN',
            'MK BULAN',
            'TMT JABATAN',
            'UNIT KERJA',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1', $h);
            $col++;
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'template-import-pegawai.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
