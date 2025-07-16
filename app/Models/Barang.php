<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang'; // ✅ kasih tahu Laravel nama tabel aslinya

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'stok',
        'harga_satuan',
        'total_harga',
        'status_asal',
        'nilai_tkdn',
    ];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiBarang::class);
    }
}
