<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TahunAjaran extends Model
{
    use HasUuids;

    protected $table = 'tahun_ajaran';
    protected $guarded = [];

    public function eskul()
    {
        return $this->hasMany(Eskul::class);
    }

    public function siswa()
    {
        return $this->hasMany(SiswaTahunAjaran::class);
    }
}
