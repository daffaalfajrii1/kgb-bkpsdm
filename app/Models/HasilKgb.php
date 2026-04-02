<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKgb extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'nomor_registrasi',
        'nama',
        'nip',
        'file_hasil',
        'tanggal_upload',
        'is_published',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}