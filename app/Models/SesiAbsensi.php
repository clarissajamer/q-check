<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SesiAbsensi extends Model
{
    use HasFactory, HasUuids;

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

    public function Jadwal()
    {
        return $this->belongsTo(JadwalEskul::class, 'jadwal_eskul_id');
    }

    public function pembuka()
    {
        return $this->belongsTo(User::class, 'dibuka_oleh');
    }

    public function qrToken()
    {
        return $this->hasMany(QrTokenAbsensi::class, 'sesi_absensi_id');
    }
    
}
        