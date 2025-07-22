<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'device_id',
        'tanggal',
        'kategori_perawatan',
        'deskripsi',
        'bukti',
    ];

    /**
     * Casting otomatis
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi: Maintenance milik satu Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
