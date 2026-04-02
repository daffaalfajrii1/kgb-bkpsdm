<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique()->nullable();
            $table->string('nama');
            $table->string('nip')->index();
            $table->string('dinas_instansi')->nullable();
            $table->string('pangkat_terakhir');
            $table->date('tmt_berkala_berikutnya')->nullable();

            $table->string('surat_pengantar_skpd')->nullable();
            $table->string('sk_cpns_legalisir')->nullable();
            $table->string('sk_pangkat_terakhir_legalisir')->nullable();
            $table->string('kgb_terakhir')->nullable();
            $table->string('sk_peninjauan_masa_kerja')->nullable();
            $table->string('skp_1_tahun_terakhir')->nullable();

            $table->string('status')->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};