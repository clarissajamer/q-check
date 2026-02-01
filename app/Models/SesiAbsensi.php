<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesiAbsensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_absensi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'jadwal_eskul_id',
        'mulai_absen',
        'selesai_absen',
        'opened_lat',
        'opened_lng',
        'dibuka_oleh',
        'status',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalEskul::class, 'jadwal_eskul_id');
    }

    public function pembuka()
    {
        return $this->belongsTo(User::class, 'dibuka_oleh');
    }
}
        