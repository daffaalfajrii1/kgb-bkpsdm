<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disiplin_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tmt_berlaku');
            $table->boolean('selesai')->default(false);
            $table->string('tingkat_hukuman'); // ringan | sedang | berat
            $table->text('hukuman_disiplin');
            $table->timestamps();

            $table->index(['user_id', 'selesai', 'tingkat_hukuman']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disiplin_pegawais');
    }
};

