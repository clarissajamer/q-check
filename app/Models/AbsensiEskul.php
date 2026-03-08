<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AbsensiEskul extends Model
{
    use HasUuids;

    protected $table = 'absensi_eskul';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'sesi_absensi_id',
        'siswa_id',
        'status',
        'sumber',
        'waktu_scan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class, 'sesi_absensi_id');
    }
}