<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'kategori',
        'spesifikasi',
        'tanggal_serah_terima',
        'no_polisi',
    ];

    /**
     * Casting otomatis untuk kolom tanggal.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_serah_terima' => 'date',
    ];

    /* -----------------------------------------------------------------
     |  RELASI MODEL
     |-----------------------------------------------------------------*/

    /**
     * Relasi: Kendaraan dimiliki oleh satu User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Kendaraan memiliki banyak Maintenance.
     *
     * @return HasMany
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
