<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk mass-assignment
     */
    protected $fillable = [
        'user_id',
        'nama',
        'kategori',            // ← ditambahkan
        'spesifikasi',
        'tanggal_serah_terima',
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
     * Device dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Device memiliki banyak Maintenance.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
