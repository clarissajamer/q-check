<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Eskul extends Model
{
    use HasUuids;

    protected $table = 'eskul';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriEskul::class, 'kategori_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaEskul::class);
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalEskul::class);
    }
}
