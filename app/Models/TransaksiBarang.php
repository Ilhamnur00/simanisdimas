<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_barang';

    protected $fillable = [
        'jenis_transaksi',
        'jumlah_barang',
        'tanggal',
        'status',
        'user_id',
        'detail_barang_id',
        'barang_id',
        'harga_satuan',
        'total_harga',
        'status_asal',
        'nilai_tkdn',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

}
