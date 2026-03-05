<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AnggotaEskul extends Model
{
    use HasUuids;

    protected $table = 'anggota_eskul';

    // ⬇️ WAJIB untuk UUID primary key
    public $incrementing = false;
    protected $keyType = 'string';

    // boleh guarded kosong karena ini internal table
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function eskul()
    {
        return $this->belongsTo(Eskul::class, 'eskul_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}