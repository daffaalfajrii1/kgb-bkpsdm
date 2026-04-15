<?php

use App\Http\Controllers\Admin\HasilKgbController;
use App\Http\Controllers\Admin\AdminPegawaiController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDisiplinPegawaiController;
use App\Http\Controllers\Admin\AdminPegawaiSkpController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Pegawai\PegawaiAuthController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Pegawai\PegawaiProfileController;
use App\Http\Controllers\Pegawai\PegawaiSkController;
use App\Http\Controllers\Pegawai\PegawaiPengajuanController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PublicPengajuanController;
use App\Http\Controllers\Public\PublicStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [PegawaiAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [PegawaiAuthController::class, 'login']);

        Route::get('/lupa-password', fn () => view('pegawai.forgot-password'))->name('password.request');
        Route::post('/lupa-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    });

    Route::middleware('pegawai')->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [PegawaiProfileController::class, 'show'])->name('profile.show');
        Route::patch('/profil/password', [PegawaiProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::get('/pengajuan', [PegawaiPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PegawaiPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PegawaiPengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}/edit', [PegawaiPengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::patch('/pengajuan/{pengajuan}', [PegawaiPengajuanController::class, 'update'])->name('pengajuan.update');

        Route::get('/sk', [PegawaiSkController::class, 'index'])->name('sk.index');

        Route::post('/logout', [PegawaiAuthController::class, 'logout'])->name('logout');
    });
});

Route::get('/cek-registrasi', [PublicStatusController::class, 'index'])->name('public.status.index');
Route::get('/cek-registrasi/hasil', [PublicStatusController::class, 'search'])->name('public.status.search');

Route::get('/sk-kgb', [PublicStatusController::class, 'skIndex'])->name('public.sk.index');
Route::get('/sk-kgb/download/{hasilKgb}', [PublicStatusController::class, 'download'])->name('public.sk.download');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [PengajuanController::class, 'dashboard'])->name('dashboard');

    Route::get('/pegawai/template', [AdminPegawaiController::class, 'template'])->name('pegawai.template');
    Route::post('/pegawai/import', [AdminPegawaiController::class, 'import'])->name('pegawai.import');
    Route::post('/pegawai/{pegawai}/reset-password', [AdminPegawaiController::class, 'resetPassword'])->name('pegawai.reset-password');
    Route::resource('pegawai', AdminPegawaiController::class)->except(['show']);

    Route::resource('admins', AdminUserController::class)->except(['show']);

    Route::resource('disiplin', AdminDisiplinPegawaiController::class)->except(['show']);

    // SKP: hanya admin (middleware admin + policy di controller). Pegawai tidak punya rute ke sini.
    Route::resource('pegawai-skp', AdminPegawaiSkpController::class)
        ->parameters(['pegawai-skp' => 'pegawaiSkpDuaTahun'])
        ->except(['show']);

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/diproses', [PengajuanController::class, 'diproses'])->name('pengajuan.diproses');
    Route::get('/pengajuan/selesai', [PengajuanController::class, 'selesaiIndex'])->name('pengajuan.selesai.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::patch('/pengajuan/{pengajuan}/proses', [PengajuanController::class, 'proses'])->name('pengajuan.proses');
    Route::patch('/pengajuan/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])->name('pengajuan.tolak');
    Route::patch('/pengajuan/{pengajuan}/selesai', [PengajuanController::class, 'selesai'])->name('pengajuan.selesai');

    Route::get('/hasil-kgb', [HasilKgbController::class, 'index'])->name('hasil-kgb.index');
    Route::get('/hasil-kgb/create', [HasilKgbController::class, 'create'])->name('hasil-kgb.create');
    Route::post('/hasil-kgb', [HasilKgbController::class, 'store'])->name('hasil-kgb.store');
});

require __DIR__.'/auth.php';