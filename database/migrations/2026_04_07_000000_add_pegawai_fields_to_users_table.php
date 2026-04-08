<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->unique()->nullable()->after('role');
            $table->string('dinas_instansi')->nullable()->after('nip');
            $table->string('pangkat_terakhir')->nullable()->after('dinas_instansi');
            $table->string('alamat')->nullable()->after('pangkat_terakhir');
            $table->string('gelar_depan')->nullable()->after('alamat');
            $table->string('gelar_belakang')->nullable()->after('gelar_depan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'dinas_instansi',
                'pangkat_terakhir',
                'alamat',
                'gelar_depan',
                'gelar_belakang',
            ]);
        });
    }
};

