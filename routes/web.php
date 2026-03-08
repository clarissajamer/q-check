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
// Imports for Guru Controllers
use App\Http\Controllers\Guru\JadwalEskulController as GuruJadwalController;
use App\Http\Controllers\Guru\SesiAbsensiController as GuruSesiController;
use App\Http\Controllers\Admin\UserController;

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

        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            // Index & Create with role parameter
            Route::get('{role}', [UserController::class, 'index'])
                ->where('role', 'admin|guru|siswa')
                ->name('index');
            Route::get('{role}/create', [UserController::class, 'create'])
                ->where('role', 'admin|guru|siswa')
                ->name('create');
            
            // Store, Update, Destroy (standard resource actions, but custom handled)
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('{user}', [UserController::class, 'update'])->name('update');
            Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
        });
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

        // Jadwal Eskul List
        Route::get('/jadwal', [GuruJadwalController::class, 'index'])->name('jadwal.index');

        // Sesi Absensi & QR
        Route::post('/jadwal/{jadwal}/buka-absensi', [GuruSesiController::class, 'buka'])->name('jadwal-eskul.buka-absensi');
        Route::get('/sesi/{sesi}/qr', [GuruSesiController::class, 'qr'])->name('sesi-absensi.qr');
        Route::post('/sesi/{sesi}/tutup', [GuruSesiController::class, 'tutup'])->name('sesi-absensi.tutup');
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