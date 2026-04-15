<?php

namespace App\Mail;

use App\Models\HasilKgb;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HasilKgbTersediaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pengajuan $pengajuan,
        public HasilKgb $hasilKgb,
        public User $pegawai
    ) {
    }

    public function build(): static
    {
        $downloadUrl = route('public.sk.download', $this->hasilKgb->id, absolute: true);

        return $this
            ->subject('KGB: Hasil Pengajuan Telah Tersedia')
            ->view('emails.hasil_kgb_tersedia')
            ->with([
                'pengajuan' => $this->pengajuan,
                'pegawai' => $this->pegawai,
                'hasilKgb' => $this->hasilKgb,
                'downloadUrl' => $downloadUrl,
            ]);
    }
}

