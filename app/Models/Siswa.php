<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Siswa extends Model
{
    use HasUuids;

    protected $table = 'siswa';
    protected $guarded = [];

    public function tahunAjaran()
    {
        return $this->hasMany(SiswaTahunAjaran::class);
    }

    public function anggotaEskul()
    {
        return $this->hasMany(AnggotaEskul::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiEskul::class);
    }
}
