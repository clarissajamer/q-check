<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KategoriEskul extends Model
{
    use HasUuids;

    protected $table = 'kategori_eskul';
    protected $guarded = [];

    public function eskul()
    {
        return $this->hasMany(Eskul::class, 'kategori_id');
    }
}
