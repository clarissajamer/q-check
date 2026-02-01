<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class JadwalEskul extends Model
{
    use HasFactory;

    protected $table = 'jadwal_eskul';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'eskul_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'latitude',
        'longitude',
        'radius_meter',
        'status',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /* ================= RELATION ================= */

    public function eskul()
    {
        return $this->belongsTo(Eskul::class, 'eskul_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sesiAbsensi()
    {
        return $this->hasMany(SesiAbsensi::class, 'jadwal_eskul_id');
    }

    /* ================= HELPER ================= */

    public function getHariAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->translatedFormat('l');
    }
}
