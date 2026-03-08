<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\SiswaDashboardController;
use App\Http\Controllers\Api\QrAbsensiController;
use App\Http\Controllers\Api\StatusAbsensiController;
use App\Http\Controllers\Siswa\IzinAbsensiController;
use App\Http\Controllers\Api\SesiAktifController;
use App\Http\Middleware\EnsureSiswa;
use App\Http\Controllers\Api\RiwayatAbsensiController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::post('/siswa/login', [SiswaApiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| SISWA API
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', EnsureSiswa::class])
    ->prefix('siswa')
    ->group(function () {

        Route::post('/logout', [SiswaApiController::class, 'logout']);

        Route::get('/dashboard', [SiswaDashboardController::class, 'index']);

        Route::get('/status-absensi', [StatusAbsensiController::class, 'status']);

        Route::post('/absensi/scan', [QrAbsensiController::class, 'scanQr']);

        Route::get('/izin', [IzinAbsensiController::class, 'index']);

        Route::post('/izin', [IzinAbsensiController::class, 'store']);

        route::get('/izin/{id}', [IzinAbsensiController::class, 'show']);

        Route::get('/sesi-aktif', [SesiAktifController::class, 'index']);

        Route::get('/riwayat-absensi', [RiwayatAbsensiController::class, 'index']);



    });

/*
|--------------------------------------------------------------------------
| TEST USER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
