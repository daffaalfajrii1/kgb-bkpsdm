<?php

use App\Http\Controllers\Admin\HasilKgbController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PublicPengajuanController;
use App\Http\Controllers\Public\PublicStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/pengajuan-kgb', [PublicPengajuanController::class, 'create'])->name('public.pengajuan.create');
Route::post('/pengajuan-kgb', [PublicPengajuanController::class, 'store'])->name('public.pengajuan.store');

Route::get('/cek-registrasi', [PublicStatusController::class, 'index'])->name('public.status.index');
Route::get('/cek-registrasi/hasil', [PublicStatusController::class, 'search'])->name('public.status.search');

Route::get('/sk-kgb', [PublicStatusController::class, 'skIndex'])->name('public.sk.index');
Route::get('/sk-kgb/download/{hasilKgb}', [PublicStatusController::class, 'download'])->name('public.sk.download');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [PengajuanController::class, 'dashboard'])->name('dashboard');

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