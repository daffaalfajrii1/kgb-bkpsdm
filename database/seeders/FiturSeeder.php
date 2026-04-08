<?php

namespace Database\Seeders;

use App\Models\HasilKgb;
use App\Models\Pengajuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FiturSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh: satu pengajuan status "diproses" + satu pengajuan status "selesai"
        // supaya menu admin langsung punya data.
        $this->seedDiproses();
        $this->seedSelesai();
    }

    private function seedDiproses(): void
    {
        $nomorRegistrasi = 'REG-20260402-0001';

        Pengajuan::updateOrCreate(
            ['nomor_registrasi' => $nomorRegistrasi],
            [
                'nama' => 'Contoh Pengajuan Diproses',
                'nip' => '198001011234567001',
                'dinas_instansi' => 'BKPSDM Contoh',
                'pangkat_terakhir' => 'IV/a',
                'tmt_berkala_berikutnya' => '2027-01-01',
                'status' => 'diproses',
            ]
        );
    }

    private function seedSelesai(): void
    {
        $nomorRegistrasi = 'REG-20260402-0002';

        $pengajuan = Pengajuan::updateOrCreate(
            ['nomor_registrasi' => $nomorRegistrasi],
            [
                'nama' => 'Contoh Pengajuan Selesai',
                'nip' => '198101011234567002',
                'dinas_instansi' => 'BKPSDM Contoh',
                'pangkat_terakhir' => 'IV/b',
                'tmt_berkala_berikutnya' => '2027-02-01',
                'status' => 'selesai',
            ]
        );

        $this->storeDummyPdfIfMissing(
            'hasil-kgb/seed-' . $nomorRegistrasi . '.pdf',
            'Hasil KGB (Seed) - ' . $pengajuan->nama
        );

        $filePath = 'hasil-kgb/seed-' . $nomorRegistrasi . '.pdf';

        HasilKgb::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            [
                'nomor_registrasi' => $pengajuan->nomor_registrasi,
                'nama' => $pengajuan->nama,
                'nip' => $pengajuan->nip,
                'file_hasil' => $filePath,
                'tanggal_upload' => date('Y-m-d'),
                'is_published' => true,
            ]
        );
    }

    private function storeDummyPdfIfMissing(string $storageRelativePath, string $title): void
    {
        if (Storage::disk('public')->exists($storageRelativePath)) {
            return;
        }

        // PDF minimal (valid secara sintaks) agar bisa dibuka saat diunduh.
        $pdf = $this->makeMinimalPdf($title);
        Storage::disk('public')->put($storageRelativePath, $pdf);
    }

    private function makeMinimalPdf(string $text): string
    {
        // Escape paranthesis untuk PDF text operator.
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);

        $objects = [];
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";

        $stream = "BT /F1 18 Tf 10 150 Td (" . $text . ") Tj ET\n";
        $objects[4] = "4 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "endstream\nendobj\n";

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
        $pdf .= "0 " . (count($ordered) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($ordered as $n) {
            $pdf .= str_pad((string)$offsets[$n], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer\n";
        $pdf .= "<< /Size " . (count($ordered) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefOffset . "\n";
        $pdf .= "%%EOF";

        return $pdf;
    }
}

