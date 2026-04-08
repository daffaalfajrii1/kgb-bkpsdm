<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gol_pangkat')->nullable()->after('pangkat_terakhir');
            $table->date('tmt_golongan')->nullable()->after('gol_pangkat');
            $table->unsignedSmallInteger('mk_tahun')->nullable()->after('tmt_golongan');
            $table->unsignedTinyInteger('mk_bulan')->nullable()->after('mk_tahun');
            $table->date('tmt_jabatan')->nullable()->after('mk_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gol_pangkat',
                'tmt_golongan',
                'mk_tahun',
                'mk_bulan',
                'tmt_jabatan',
            ]);
        });
    }
};
