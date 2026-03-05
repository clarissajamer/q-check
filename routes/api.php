<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\SiswaDashboardController;
use App\Http\Controllers\Api\QrAbsensiController;
use App\Http\Middleware\EnsureSiswa;

Route::post('/siswa/login', [SiswaApiController::class, 'login']);

Route::middleware(['auth:sanctum', EnsureSiswa::class])->group(function () {
    Route::post('/siswa/logout', [SiswaApiController::class, 'logout']);
    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index']); 
    Route::post('/siswa/absensi/scan', [QrAbsensiController::class, 'scanQr']);
});

// Route QrAbsensiController dihapus agar tidak duplikat

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

