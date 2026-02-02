<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EskulController;
use App\Http\Controllers\Admin\JadwalEskulController;
use App\Http\Controllers\Admin\SesiAbsensiController;
use App\Http\Controllers\Admin\KategoriEskulController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Siswa\AbsensiController;
use App\Http\Controllers\Siswa\SiswaDashboardController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('eskul', EskulController::class);

        Route::resource('jadwal-eskul', JadwalEskulController::class);

        Route::resource('kategori-eskul', KategoriEskulController::class);

        Route::resource('tahun-ajaran', TahunAjaranController::class);

        Route::post(
            'jadwal-eskul/{jadwal}/buka-absensi',
            [SesiAbsensiController::class, 'buka']
        )->name('jadwal-eskul.buka-absensi');

        Route::get(
            'sesi-absensi/{sesi}/qr',
            [SesiAbsensiController::class, 'qr']
        )->name('sesi-absensi.qr');

        Route::post(
            'sesi-absensi/{sesi}/tutup',
            [SesiAbsensiController::class, 'tutup']
        )->name('sesi-absensi.tutup');
    });

/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('guru.dashboard'))
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| SISWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/absen', [AbsensiController::class, 'absen'])
            ->name('absen');
    });