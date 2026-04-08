<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisiplinPegawai extends Model
{
    protected $table = 'disiplin_pegawais';

    protected $fillable = [
        'user_id',
        'tmt_berlaku',
        'tmt_selesai',
        'selesai',
        'tingkat_hukuman',
        'hukuman_disiplin',
    ];

    protected $casts = [
        'tmt_berlaku' => 'date',
        'tmt_selesai' => 'date',
        'selesai' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

