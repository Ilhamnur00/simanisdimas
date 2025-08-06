<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPerawatan extends Model
{
    use HasFactory;
    protected $table = 'perawatan_kendaraan';

 protected $fillable = [
    'kendaraan_id',
    'tanggal',
    'kategori_perawatan', // harus sama dengan nama kolom di tabel
    'deskripsi',
    'bukti',              // harus 'bukti', bukan 'lampiran'
];


    /**
     * Relasi ke model Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
