<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class AbsensiEskul extends Model
{
    use HasFactory;

    protected $table = 'absensi_eskul';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'sesi_absensi_id',
        'siswa_id',
        'latitude',
        'longitude',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /* ========= RELATION ========= */

    public function sesi()
    {
        return $this->belongsTo(SesiAbsensi::class, 'sesi_absensi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
