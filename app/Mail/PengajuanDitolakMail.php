<?php

namespace App\Mail;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanDitolakMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  list<string>  $perbaikanItems
     */
    public function __construct(
        public Pengajuan $pengajuan,
        public User $pegawai,
        public array $perbaikanItems
    ) {
    }

    /**
     * @return array<string,string>
     */
    public static function labelPerbaikanItems(): array
    {
        return [
            'tmt_berkala_berikutnya' => 'TMT berkala berikutnya',
            'surat_pengantar_skpd' => 'Surat Pengantar SKPD',
            'sk_cpns_legalisir' => 'SK CPNS legalisir',
            'sk_pangkat_terakhir_legalisir' => 'SK Pangkat terakhir legalisir',
            'kgb_terakhir' => 'KGB terakhir',
            'sk_peninjauan_masa_kerja' => 'SK Peninjauan Masa Kerja (opsional)',
            'skp_1_tahun_terakhir' => 'SKP 1 tahun terakhir',
        ];
    }

    public function build(): static
    {
        $labels = self::labelPerbaikanItems();
        $perbaikanLabels = array_values(array_map(
            fn (string $key) => $labels[$key] ?? $key,
            $this->perbaikanItems
        ));

        return $this
            ->subject('KGB: Pengajuan Dikembalikan untuk Perbaikan')
            ->view('emails.pengajuan_ditolak')
            ->with([
                'pengajuan' => $this->pengajuan,
                'pegawai' => $this->pegawai,
                'perbaikanLabels' => $perbaikanLabels,
            ]);
    }
}

