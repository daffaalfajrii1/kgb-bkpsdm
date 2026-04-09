<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Row;
use Throwable;

class PegawaiImport implements
    OnEachRow,
    SkipsEmptyRows,
    SkipsOnError,
    ShouldQueue,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    WithColumnLimit,
    WithLimit
{
    use SkipsErrors;

    /**
     * Import per-baris agar hemat memori & aman untuk ribuan data.
     * Dengan chunk, Laravel-Excel tidak akan load seluruh file ke RAM.
     */
    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        $nip = trim((string) ($data['nip'] ?? ''));
        $name = trim((string) ($data['nama'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        // Validasi minimal inline agar hemat memory (tanpa menyimpan failures array besar)
        if ($nip === '' || $name === '') {
            return;
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return;
        }

        // Normalisasi minimal supaya konsisten
        $email = $email !== '' ? Str::lower($email) : null;

        $payload = [
            'nip' => $nip,
            'name' => $name,
            'email' => $email,
            'pangkat_terakhir' => trim((string) ($data['golongan_pangkat_terakhir'] ?? '')) ?: null,
            'tmt_golongan' => $data['tmt_golongan'] ?? null,
            'mk_tahun' => $data['mk_tahun'] ?? null,
            'mk_bulan' => $data['mk_bulan'] ?? null,
            'tmt_jabatan' => $data['tmt_jabatan'] ?? null,
            'unor_nama' => trim((string) ($data['unor_nama'] ?? '')) ?: null,
        ];

        // Upsert by NIP. Email nullable; jika email dipakai user lain maka akan gagal karena unique constraint.
        $user = User::query()->where('nip', $payload['nip'])->first();
        if ($user) {
            $user->update([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'role' => 'pegawai',
                'pangkat_terakhir' => $payload['pangkat_terakhir'],
                'tmt_golongan' => $payload['tmt_golongan'],
                'mk_tahun' => $payload['mk_tahun'],
                'mk_bulan' => $payload['mk_bulan'],
                'tmt_jabatan' => $payload['tmt_jabatan'],
                'unor_nama' => $payload['unor_nama'],
            ]);

            return;
        }

        User::create([
            'nip' => $payload['nip'],
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make('Password123!'),
            'role' => 'pegawai',
            'pangkat_terakhir' => $payload['pangkat_terakhir'],
            'tmt_golongan' => $payload['tmt_golongan'],
            'mk_tahun' => $payload['mk_tahun'],
            'mk_bulan' => $payload['mk_bulan'],
            'tmt_jabatan' => $payload['tmt_jabatan'],
            'unor_nama' => $payload['unor_nama'],
        ]);
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    // Limit membaca kolom agar tidak iterasi sampai kolom terakhir di sheet
    // (mencegah memory exhaustion pada file besar).
    public function endColumn(): string
    {
        // A..I = 9 field yang kita import
        return 'I';
    }

    public function limit(): int
    {
        // Batasi scanning baris agar sheet dengan formatting panjang tidak memicu OOM.
        // 20.000 jauh di atas kebutuhan 3.000+ data.
        return 20000;
    }

    public function onError(Throwable $e): void
    {
        // biarkan package menangani pencatatan error internal
    }
}

