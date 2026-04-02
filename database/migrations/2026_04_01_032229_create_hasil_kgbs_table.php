<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kgbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_registrasi')->nullable();
            $table->string('nama');
            $table->string('nip')->index();
            $table->string('file_hasil');
            $table->date('tanggal_upload')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kgbs');
    }
};