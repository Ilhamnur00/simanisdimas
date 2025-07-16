<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiBarang extends Model
{
    protected $table = 'transaksi_barang';

    protected $primaryKey = 'no_transaksi';

    protected $fillable = [
        'user_id',
        'barang_id',
        'jenis_transaksi',
        'jumlah_barang',
        'harga_satuan',
        'total_harga',
        'status',
        'tanggal'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
