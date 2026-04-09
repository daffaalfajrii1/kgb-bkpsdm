<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disiplin_block_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 100)->index();
            $table->string('nama_pegawai', 255);
            $table->string('jenis', 40)->index(); // skp | hukuman | middleware
            $table->string('alasan', 500);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disiplin_block_logs');
    }
};
