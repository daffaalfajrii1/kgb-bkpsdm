<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create (or update) admin accounts from document list.
     */
    public function run(): void
    {
        $defaultPassword = env('ADMIN_PASSWORD', 'Admin12345');

        $admins = [
            ['name' => 'DERI SUHENDRA, SE', 'email' => 'the_ry84@yahoo.com'],
            ['name' => 'DEDI SUSANTO, SH', 'email' => 'dedi.lewis@gmail.com'],
            ['name' => 'IZWANZA, S.Sos', 'email' => 'iz.one.za@gmail.com'],
            ['name' => 'RAKHMAD BASUKI SANJAYA, S.KM, MPH', 'email' => 'rakhmadbasukisanjaya@gmail.com'],
            ['name' => 'MEDI TALO', 'email' => 'meditalo2020@gmail.com'],
            ['name' => 'RIZKI SEPTIANTI, S.Tr.I.P', 'email' => 'rizkiseptianti089@gmail.com'],
            ['name' => 'SANDI GUNAWAN, S,Sos', 'email' => 'sandilebohang@gmail.com'],
            ['name' => 'RISCHA APRIYANTI,S.Sos', 'email' => 'rischa.naura1986@gmail.com'],
            ['name' => 'ADE ALBAR', 'email' => 'adealbar993@gmail.com'],
            ['name' => 'NUR KHOLIS KAMAL', 'email' => 'lisfarera17@gmail.com'],
            ['name' => 'INDAH KUSUMA SARI', 'email' => 'indahskp2025@gmail.com'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'role' => 'admin',
                    'password' => Hash::make($defaultPassword),
                ]
            );
        }
    }
}

