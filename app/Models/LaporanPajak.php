<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanPajak extends Model
{
    use HasFactory;
    
    protected $table = 'pajak_kendaraan';

    protected $fillable = [
    'kendaraan_id',
    'jenis_pajak',
    'tanggal',
    'deskripsi',
];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
