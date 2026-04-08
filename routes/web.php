<?php

use App\Http\Controllers\Admin\HasilKgbController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Pegawai\PegawaiAuthController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;
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
    });

    Route::middleware('pegawai')->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengajuan', [PegawaiPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PegawaiPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PegawaiPengajuanController::class, 'store'])->name('pengajuan.store');

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

    Route::resource('admins', AdminUserController::class)->except(['show']);

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/diproses', [PengajuanController::class, 'diproses'])->name('pengajuan.diproses');
    Route::get('/pengajuan/selesai', [PengajuanController::class, 'selesaiIndex'])->name('pengajuan.selesai.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::patch('/pengajuan/{pengajuan}/proses', [PengajuanController::class, 'proses'])->name('pengajuan.proses');
    Route::patch('/pengajuan/{pengajuan}/selesai', [PengajuanController::class, 'selesai'])->name('pengajuan.selesai');

    Route::get('/hasil-kgb', [HasilKgbController::class, 'index'])->name('hasil-kgb.index');
    Route::get('/hasil-kgb/create', [HasilKgbController::class, 'create'])->name('hasil-kgb.create');
    Route::post('/hasil-kgb', [HasilKgbController::class, 'store'])->name('hasil-kgb.store');
});

require __DIR__.'/auth.php';