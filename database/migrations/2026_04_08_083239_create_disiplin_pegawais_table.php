<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel sudah dibuat oleh 2026_04_08_000002_create_disiplin_pegawais_table;
        // migrasi ini dibiarkan no-op agar tidak error "table already exists".
        if (Schema::hasTable('disiplin_pegawais')) {
            return;
        }

        Schema::create('disiplin_pegawais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disiplin_pegawais');
    }
};
