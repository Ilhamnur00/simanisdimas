<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};


class Kendaraan extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk mass-assignment
     */
    protected $fillable = [
        'user_id',
        'nama',
        'kategori',
        'spesifikasi',
        'tanggal_serah_terima',
        'no_polisi'
    ];

    /**
     * Casting otomatis – memudahkan manipulasi tanggal
     */
    protected $casts = [
        'tanggal_serah_terima' => 'date',
    ];

    /* -----------------------------------------------------------------
     |  RELASI
     |-----------------------------------------------------------------*/

    /**
     * Kendaraan dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kendaraan memiliki banyak Maintenance.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
