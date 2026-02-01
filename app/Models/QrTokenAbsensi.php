<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class QrTokenAbsensi extends Model
{
    use HasUuids;

    protected $table = 'qr_token_absensi';
    protected $guarded = [];

    public function sesi()
    {
        return $this->belongsTo(SesiAbsensi::class, 'sesi_absensi_id');
    }
}
