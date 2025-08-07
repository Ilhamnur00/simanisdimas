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
        'tanggal_pajak'
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
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pajaks(): HasMany
    {
        return $this->hasMany(LaporanPajak::class);
    }

    /**
     * Relasi: Kendaraan memiliki banyak Laporan Perawatan.
     */
    public function riwayatPerawatan()
{
    return $this->hasMany(LaporanPerawatan::class);
}
}
