<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai_skp_dua_tahuns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('tahun_terbaru');
            $table->string('predikat_terbaru', 80);
            $table->unsignedSmallInteger('tahun_sebelumnya');
            $table->string('predikat_sebelumnya', 80);
            $table->timestamps();

            $table->unique('user_id');
            $table->index(['tahun_terbaru', 'tahun_sebelumnya']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai_skp_dua_tahuns');
    }
};
