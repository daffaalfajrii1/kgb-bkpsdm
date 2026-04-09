<?php

namespace Database\Seeders;

use App\Models\PegawaiSkpDuaTahun;
use App\Models\User;
use App\Services\PegawaiAksesDisiplinService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Pegawai uji coba: predikat SKP 2 tahun terakhir = Buruk (login seharusnya ditolak).
 */
class DummyPegawaiSkpBurukSeeder extends Seeder
{
    public function run(): void
    {
        $nip = '9999000000000001';
        $passwordPlain = '9999000000000001';

        $user = User::updateOrCreate(
            ['nip' => $nip],
            [
                'name' => 'Dummy Uji SKP Buruk',
                'email' => 'dummy-skp-buruk-uji@local.test',
                'role' => 'pegawai',
                'password' => Hash::make($passwordPlain),
                'dinas_instansi' => 'Uji coba manajemen disiplin',
            ]
        );

        [$tahunBaru, $tahunLama] = PegawaiAksesDisiplinService::pasanganTahunSkpOtomatis();

        PegawaiSkpDuaTahun::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tahun_terbaru' => $tahunBaru,
                'tahun_sebelumnya' => $tahunLama,
                'predikat_terbaru' => 'Buruk',
                'predikat_sebelumnya' => 'Buruk',
            ]
        );

        if ($this->command) {
            $this->command->info('Dummy pegawai (SKP Buruk) siap untuk uji login.');
            $this->command->table(
                ['Keterangan', 'Nilai'],
                [
                    ['Login pegawai memakai', 'NIP + password (bukan email)'],
                    ['NIP', $nip],
                    ['Password', $passwordPlain],
                    ['Periode SKP tersimpan', $tahunBaru.' / '.$tahunLama],
                    ['Predikat', 'Buruk / Buruk'],
                ]
            );
        }
    }
}
