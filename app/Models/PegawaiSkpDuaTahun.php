<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PegawaiSkpDuaTahun extends Model
{
    protected $table = 'pegawai_skp_dua_tahuns';

    protected $fillable = [
        'user_id',
        'tahun_terbaru',
        'predikat_terbaru',
        'tahun_sebelumnya',
        'predikat_sebelumnya',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
