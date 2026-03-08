<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IzinAbsensi extends Model
{
    use HasUuids;

    protected $table = 'izin_absensi';

    protected $fillable = [
        'siswa_id',
        'sesi_absensi_id',
        'jenis_izin',
        'alasan',
        'bukti_file',
        'status',
        'tanggal_pengajuan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class);
    }
}