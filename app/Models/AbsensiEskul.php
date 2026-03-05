<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AbsensiEskul extends Model
{
    use HasUuids; // Karena tabel kamu pakai UUID

    protected $table = 'absensi_eskul';

    // WAJIB: Tambahkan 'waktu_scan' ke daftar ini agar tidak diblokir Laravel
    protected $fillable = [
        'id',
        'sesi_absensi_id',
        'siswa_id',
        'status',
        'waktu_scan', 
    ];
}