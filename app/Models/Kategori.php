<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['kategori_id', 'nama_kategori'];

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
