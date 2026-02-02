<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriEskul extends Model
{
    // Removed HasUuids to default to auto-incrementing integer

    protected $table = 'kategori_eskul';
    protected $guarded = [];

    public function eskul()
    {
        return $this->hasMany(Eskul::class, 'kategori_id');
    }
}
