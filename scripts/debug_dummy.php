<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$nip = '9999000000000001';
$password = 'TestBuruk123';

$u = App\Models\User::query()->where('nip', $nip)->first();

echo "DB connection: " . config('database.default') . PHP_EOL;
echo "env(DB_CONNECTION): " . (string) env('DB_CONNECTION') . PHP_EOL;
echo "env(DB_DATABASE): " . (string) env('DB_DATABASE') . PHP_EOL;
echo "sqlite.database: " . (string) config('database.connections.sqlite.database') . PHP_EOL;
echo "NIP: {$nip}" . PHP_EOL;
echo "User exists: " . ($u ? 'YES' : 'NO') . PHP_EOL;
if ($u) {
    echo "User id/name/role/email: {$u->id} / {$u->name} / {$u->role} / {$u->email}" . PHP_EOL;
    echo "Hash check: " . (Illuminate\Support\Facades\Hash::check($password, $u->password) ? 'OK' : 'FAIL') . PHP_EOL;
}

$skp = null;
if ($u) {
    $skp = App\Models\PegawaiSkpDuaTahun::query()->where('user_id', $u->id)->first();
}
echo "SKP exists: " . ($skp ? 'YES' : 'NO') . PHP_EOL;
if ($skp) {
    echo "SKP periode: {$skp->tahun_terbaru} / {$skp->tahun_sebelumnya}" . PHP_EOL;
    echo "SKP predikat: {$skp->predikat_terbaru} / {$skp->predikat_sebelumnya}" . PHP_EOL;
}

try {
    $totalSkp = Illuminate\Support\Facades\DB::table('pegawai_skp_dua_tahuns')->count();
    echo "Total rows pegawai_skp_dua_tahuns: {$totalSkp}" . PHP_EOL;
} catch (Throwable $e) {
    echo "Query table pegawai_skp_dua_tahuns failed: " . $e->getMessage() . PHP_EOL;
}

