<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EskulController;
use App\Http\Controllers\Admin\JadwalEskulController;
use App\Http\Controllers\Admin\SesiAbsensiController;
use App\Http\Controllers\Siswa\AbsensiController;

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

        Route::post(
            'jadwal-eskul/{jadwal}/buka-absensi',
            [SesiAbsensiController::class, 'store']
        )->name('jadwal-eskul.buka-absensi');

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

        Route::get('/dashboard', fn () => view('siswa.dashboard'))
            ->name('dashboard');

        Route::post('/absen', [AbsensiController::class, 'absen'])
            ->name('absen');
    });
