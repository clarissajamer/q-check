<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AnggotaEskul extends Model
{
    use HasUuids;

    protected $table = 'anggota_eskul';
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }
}
