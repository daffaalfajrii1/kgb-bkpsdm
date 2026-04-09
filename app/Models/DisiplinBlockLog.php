<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisiplinBlockLog extends Model
{
    protected $fillable = [
        'nip',
        'nama_pegawai',
        'jenis',
        'alasan',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }
}
