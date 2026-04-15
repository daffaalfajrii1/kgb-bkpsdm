<?php

namespace Database\Seeders;

use App\Models\PegawaiSkpDuaTahun;
use App\Models\Pengajuan;
use App\Models\User;
use App\Services\PegawaiAksesDisiplinService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Data uji fitur penolakan pengajuan (admin: Tolak & Kembalikan).
 *
 * Jalankan: php artisan db:seed --class=DummyPengajuanTolakSeeder
 */
class DummyPengajuanTolakSeeder extends Seeder
{
    private const NIP = '9999000000000002';

    private const NOMOR_DIPROSES = 'REG-UJITOLAK-DIPROSES';

    private const NOMOR_DITOLAK = 'REG-UJITOLAK-DITOLAK';

    public function run(): void
    {
        $passwordPlain = self::NIP;

        $user = User::updateOrCreate(
            ['nip' => self::NIP],
            [
                'name' => 'Dummy Uji Tolak Pengajuan',
                'email' => 'dummy-uji-tolak-pengajuan@local.test',
                'role' => 'pegawai',
                'password' => Hash::make($passwordPlain),
                'dinas_instansi' => 'Uji coba penolakan pengajuan',
                'gol_pangkat' => 'III/c',
            ]
        );

        [$tahunBaru, $tahunLama] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();

        PegawaiSkpDuaTahun::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tahun_terbaru' => $tahunBaru,
                'tahun_sebelumnya' => $tahunLama,
                'predikat_terbaru' => 'Baik',
                'predikat_sebelumnya' => 'Baik',
            ]
        );

        $paths = $this->seedDummyPengajuanPdfs();

        Pengajuan::updateOrCreate(
            ['nomor_registrasi' => self::NOMOR_DIPROSES],
            [
                'nama' => $user->name,
                'nip' => $user->nip,
                'dinas_instansi' => $user->dinas_instansi,
                'pangkat_terakhir' => $user->gol_pangkat ?? $user->pangkat_terakhir ?? 'III/c',
                'tmt_berkala_berikutnya' => now()->addMonths(6)->format('Y-m-d'),
                'status' => 'diproses',
                'catatan_admin' => null,
                'surat_pengantar_skpd' => $paths['surat_pengantar_skpd'],
                'sk_cpns_legalisir' => $paths['sk_cpns_legalisir'],
                'sk_pangkat_terakhir_legalisir' => $paths['sk_pangkat_terakhir_legalisir'],
                'kgb_terakhir' => $paths['kgb_terakhir'],
                'sk_peninjauan_masa_kerja' => $paths['sk_peninjauan_masa_kerja'],
                'skp_1_tahun_terakhir' => $paths['skp_1_tahun_terakhir'],
            ]
        );

        Pengajuan::updateOrCreate(
            ['nomor_registrasi' => self::NOMOR_DITOLAK],
            [
                'nama' => $user->name,
                'nip' => $user->nip,
                'dinas_instansi' => $user->dinas_instansi,
                'pangkat_terakhir' => $user->gol_pangkat ?? $user->pangkat_terakhir ?? 'III/c',
                'tmt_berkala_berikutnya' => now()->addYear()->format('Y-m-d'),
                'status' => 'ditolak',
                'catatan_admin' => 'Contoh catatan penolakan uji coba: mohon unggah ulang SK pangkat yang lebih jelas dan terbaca (minimal 10 karakter).',
                'surat_pengantar_skpd' => $paths['surat_pengantar_skpd'],
                'sk_cpns_legalisir' => $paths['sk_cpns_legalisir'],
                'sk_pangkat_terakhir_legalisir' => $paths['sk_pangkat_terakhir_legalisir'],
                'kgb_terakhir' => $paths['kgb_terakhir'],
                'sk_peninjauan_masa_kerja' => $paths['sk_peninjauan_masa_kerja'],
                'skp_1_tahun_terakhir' => $paths['skp_1_tahun_terakhir'],
            ]
        );

        if ($this->command) {
            $this->command->info('Dummy pengajuan untuk uji penolakan siap.');
            $this->command->table(
                ['Keterangan', 'Nilai'],
                [
                    ['Login pegawai', 'NIP + password (sama dengan NIP)'],
                    ['NIP', self::NIP],
                    ['Password', $passwordPlain],
                    ['Pengajuan diproses (uji tombol Tolak di admin)', self::NOMOR_DIPROSES],
                    ['Pengajuan ditolak (uji perbaikan berkas di pegawai)', self::NOMOR_DITOLAK],
                ]
            );
        }
    }

    /**
     * @return array<string, string>
     */
    private function seedDummyPengajuanPdfs(): array
    {
        $base = 'pengajuan/seed-uji-tolak';
        $map = [
            'surat_pengantar_skpd' => $base.'/surat-pengantar.pdf',
            'sk_cpns_legalisir' => $base.'/sk-cpns.pdf',
            'sk_pangkat_terakhir_legalisir' => $base.'/sk-pangkat.pdf',
            'kgb_terakhir' => $base.'/kgb-terakhir.pdf',
            'sk_peninjauan_masa_kerja' => $base.'/sk-peninjauan-mk.pdf',
            'skp_1_tahun_terakhir' => $base.'/skp.pdf',
        ];

        foreach ($map as $rel) {
            $this->storeDummyPdfIfMissing($rel, 'Dummy uji tolak — '.$rel);
        }

        return $map;
    }

    private function storeDummyPdfIfMissing(string $storageRelativePath, string $title): void
    {
        if (Storage::disk('public')->exists($storageRelativePath)) {
            return;
        }

        Storage::disk('public')->put($storageRelativePath, $this->makeMinimalPdf($title));
    }

    private function makeMinimalPdf(string $text): string
    {
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);

        $objects = [];
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";

        $stream = "BT /F1 18 Tf 10 150 Td (".$text.') Tj ET'."\n";
        $objects[4] = "4 0 obj\n<< /Length ".strlen($stream)." >>\nstream\n".$stream."endstream\nendobj\n";

        $objects[3] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 200 200] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>\nendobj\n";
        $objects[5] = "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        $ordered = [1, 2, 3, 4, 5];
        foreach ($ordered as $n) {
            $offsets[$n] = strlen($pdf);
            $pdf .= $objects[$n];
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= '0 '.(count($ordered) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($ordered as $n) {
            $pdf .= str_pad((string) $offsets[$n], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        $pdf .= "trailer\n";
        $pdf .= '<< /Size '.(count($ordered) + 1).' /Root 1 0 R >>'."\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefOffset."\n";
        $pdf .= '%%EOF';

        return $pdf;
    }
}
