<?php

namespace App\Mail;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengajuanDiprosesMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pengajuan $pengajuan,
        public User $pegawai
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject('KGB: Pengajuan Anda Diproses')
            ->view('emails.pengajuan_diproses')
            ->with([
                'pengajuan' => $this->pengajuan,
                'pegawai' => $this->pegawai,
            ]);
    }
}

