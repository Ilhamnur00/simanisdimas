<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    use HasFactory;

    protected $table = 'detail_barang';

    protected $fillable = [
        'barang_id',
        'status_asal',
        'nilai_tkdn',
        'harga_satuan',
        'total_harga',
        'jumlah',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function transaksiBarang()
    {
        return $this->hasOne(TransaksiBarang::class);
    }

}
