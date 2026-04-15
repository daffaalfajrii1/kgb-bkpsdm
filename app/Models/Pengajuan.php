<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'nomor_registrasi',
        'nama',
        'nip',
        'dinas_instansi',
        'pangkat_terakhir',
        'tmt_berkala_berikutnya',
        'status',
        'catatan_admin',
        'perbaikan_items',
        'surat_pengantar_skpd',
        'sk_cpns_legalisir',
        'sk_pangkat_terakhir_legalisir',
        'kgb_terakhir',
        'sk_peninjauan_masa_kerja',
        'skp_1_tahun_terakhir',
    ];

    protected $casts = [
        'perbaikan_items' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pengajuan) {
            if (!$pengajuan->nomor_registrasi) {
                $lastId = self::max('id') + 1;
                $pengajuan->nomor_registrasi = 'REG-' . now()->format('Ymd') . '-' . str_pad((string) $lastId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function hasilKgb()
    {
        return $this->hasOne(HasilKgb::class);
    }
}