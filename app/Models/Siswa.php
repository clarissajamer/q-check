<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Siswa extends Model
{
    use HasUuids;

    protected $table = 'siswa';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nama',
        'nis',
        'tahun_ajaran_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function anggotaEskul()
    {
        return $this->hasMany(AnggotaEskul::class, 'siswa_id');
    }

    public function absensiEskul()
    {
        return $this->hasMany(AbsensiEskul::class, 'siswa_id');
    }

    public function izinAbsensi()
    {
        return $this->hasMany(IzinAbsensi::class, 'siswa_id');
    }

    public function eskul()
    {
        return $this->belongsToMany(
            Eskul::class,
            'anggota_eskul',
            'siswa_id',
            'eskul_id'
        )
        ->wherePivot('status', 'aktif')
        ->withPivot('id', 'tahun_ajaran_id', 'status')
        ->withTimestamps();
    }
}