<?php

namespace App\Policies;

use App\Models\PegawaiSkpDuaTahun;
use App\Models\User;

class PegawaiSkpDuaTahunPolicy
{
    /**
     * Input, ubah, dan hapus data SKP hanya untuk akun admin (bukan pegawai).
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, PegawaiSkpDuaTahun $pegawaiSkpDuaTahun): bool
    {
        return $user->role === 'admin';
    }
}
