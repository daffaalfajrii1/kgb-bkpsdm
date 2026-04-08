<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disiplin_pegawais', function (Blueprint $table) {
            $table->date('tmt_selesai')->nullable()->after('tmt_berlaku');
        });
    }

    public function down(): void
    {
        Schema::table('disiplin_pegawais', function (Blueprint $table) {
            $table->dropColumn('tmt_selesai');
        });
    }
};

