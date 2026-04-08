<?php

namespace App\Services;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class PegawaiExcelImporter
{
    /**
     * @return array{imported: int, updated: int, skipped: int, errors: list<string>}
     */
    public function import(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if ($rows === []) {
            return ['imported' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => ['File kosong.']];
        }

        $firstRow = array_shift($rows);
        $map = $this->buildHeaderMap($firstRow);

        if (! isset($map['nip'], $map['nama'])) {
            return [
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['Header tidak valid. Pastikan kolom NIP dan NAMA ada (sesuai format Excel).'],
            ];
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $rowNum = 2;
        foreach ($rows as $row) {
            try {
                $nipRaw = $this->cell($row, $map['nip'] ?? null);
                $nama = $this->cell($row, $map['nama'] ?? null);

                if ($nipRaw === '' || $nama === '') {
                    $skipped++;
                    $rowNum++;
                    continue;
                }

                $nip = $this->cleanNip($nipRaw);
                if ($nip === '') {
                    $errors[] = "Baris {$rowNum}: NIP tidak valid.";
                    $skipped++;
                    $rowNum++;
                    continue;
                }
                $email = strtolower(trim((string) $this->cell($row, $map['email'] ?? null)));
                if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris {$rowNum}: email tidak valid atau kosong (NIP {$nip}).";
                    $skipped++;
                    $rowNum++;
                    continue;
                }

                $golPangkat = trim((string) $this->cell($row, $map['gol_pangkat'] ?? null));
                $tmtGol = $this->parseDate($row, $map['tmt_golongan'] ?? null);
                $mkTahun = $this->parseInt($this->cell($row, $map['mk_tahun'] ?? null));
                $mkBulan = $this->parseInt($this->cell($row, $map['mk_bulan'] ?? null));
                $tmtJabatan = $this->parseDate($row, $map['tmt_jabatan'] ?? null);
                $unit = trim((string) $this->cell($row, $map['unit_kerja'] ?? null));

                $exists = User::query()->where('nip', $nip)->first();

                $payload = [
                    'name' => $nama,
                    'email' => $email,
                    'role' => 'pegawai',
                    'password' => $nip,
                    'dinas_instansi' => $unit !== '' ? $unit : null,
                    'pangkat_terakhir' => $golPangkat !== '' ? $golPangkat : null,
                    'gol_pangkat' => $golPangkat !== '' ? $golPangkat : null,
                    'tmt_golongan' => $tmtGol,
                    'mk_tahun' => $mkTahun,
                    'mk_bulan' => $mkBulan,
                    'tmt_jabatan' => $tmtJabatan,
                ];

                if ($exists) {
                    if ($exists->role !== 'pegawai') {
                        $errors[] = "Baris {$rowNum}: NIP {$nip} sudah dipakai akun non-pegawai.";
                        $skipped++;
                        $rowNum++;
                        continue;
                    }
                    $dupEmail = User::query()
                        ->where('email', $email)
                        ->where('id', '!=', $exists->id)
                        ->exists();
                    if ($dupEmail) {
                        $errors[] = "Baris {$rowNum}: email {$email} sudah dipakai pengguna lain.";
                        $skipped++;
                        $rowNum++;
                        continue;
                    }
                    $exists->fill($payload);
                    $exists->save();
                    $updated++;
                } else {
                    if (User::query()->where('email', $email)->exists()) {
                        $errors[] = "Baris {$rowNum}: email {$email} sudah terdaftar.";
                        $skipped++;
                        $rowNum++;
                        continue;
                    }
                    User::create(array_merge($payload, ['nip' => $nip]));
                    $imported++;
                }
            } catch (Throwable $e) {
                $errors[] = "Baris {$rowNum}: ".$e->getMessage();
                $skipped++;
            }
            $rowNum++;
        }

        return compact('imported', 'updated', 'skipped', 'errors');
    }

    /**
     * @param  array<string, mixed>  $headerRow
     * @return array<string, string> key => column letter
     */
    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $col => $label) {
            $key = $this->normalizeHeader((string) $label);
            if ($key === '') {
                continue;
            }
            if ($key === 'NIP') {
                $map['nip'] = $col;
            } elseif ($key === 'NAMA' || str_starts_with($key, 'NAMA ')) {
                $map['nama'] = $col;
            } elseif ($key === 'EMAIL' || str_starts_with($key, 'EMAIL ')) {
                $map['email'] = $col;
            } elseif (str_contains($key, 'GOL') && str_contains($key, 'PANGKAT')) {
                $map['gol_pangkat'] = $col;
            } elseif (str_contains($key, 'TMT') && str_contains($key, 'GOLONGAN')) {
                $map['tmt_golongan'] = $col;
            } elseif (str_contains($key, 'MK') && str_contains($key, 'TAHUN')) {
                $map['mk_tahun'] = $col;
            } elseif (str_contains($key, 'MK') && str_contains($key, 'BULAN')) {
                $map['mk_bulan'] = $col;
            } elseif (str_contains($key, 'TMT') && str_contains($key, 'JABATAN')) {
                $map['tmt_jabatan'] = $col;
            } elseif (str_contains($key, 'UNIT') && str_contains($key, 'KERJA')) {
                $map['unit_kerja'] = $col;
            }
        }

        return $map;
    }

    private function normalizeHeader(string $label): string
    {
        $label = str_replace(['.', '/'], ' ', $label);

        return strtoupper(trim(preg_replace('/\s+/', ' ', $label)));
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function cell(array $row, ?string $col): string
    {
        if ($col === null || ! isset($row[$col])) {
            return '';
        }

        $v = $row[$col];

        return is_scalar($v) ? trim((string) $v) : '';
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function parseDate(array $row, ?string $col): ?string
    {
        if ($col === null || ! isset($row[$col])) {
            return null;
        }
        $v = $row[$col];
        if ($v === null || $v === '') {
            return null;
        }
        if (is_numeric($v)) {
            try {
                $dt = ExcelDate::excelToDateTimeObject((float) $v);

                return $dt->format('Y-m-d');
            } catch (Throwable) {
                return null;
            }
        }
        $s = trim((string) $v);
        $ts = strtotime(str_replace('/', '-', $s));

        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function parseInt(?string $v): ?int
    {
        if ($v === null || $v === '') {
            return null;
        }
        if (! is_numeric($v)) {
            return null;
        }

        return (int) $v;
    }

    private function cleanNip(string $nipRaw): string
    {
        // Excel sering menambahkan tanda petik di depan untuk memaksa string: '1980...
        $nipRaw = trim($nipRaw);
        $nipRaw = ltrim($nipRaw, "'’`");
        // Bersihkan semua selain angka (spasi, titik, dll)
        $nip = preg_replace('/[^0-9]/', '', $nipRaw) ?? '';

        return trim($nip);
    }
}
