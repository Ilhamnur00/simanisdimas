<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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
        'tanggal',
        'status_asal',
        'nilai_tkdn',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    protected static function booted(): void
    {
        // Set user otomatis saat create
        static::creating(function ($transaksi) {
            if (Auth::check()) {
                $transaksi->user_id = Auth::id();
            }

            // Hitung total_harga otomatis jika jenis = masuk
            if ($transaksi->jenis_transaksi === 'masuk') {
                $transaksi->total_harga = $transaksi->jumlah_barang * $transaksi->harga_satuan;
            }
        });

        // Update stok jika status disetujui
        static::updated(function ($transaksi) {
            if (
                $transaksi->status === 'approved' &&
                $transaksi->isDirty('status')
            ) {
                $barang = $transaksi->barang;

                if ($transaksi->jenis_transaksi === 'masuk') {
                    $barang->stok += $transaksi->jumlah_barang;
                } elseif ($transaksi->jenis_transaksi === 'keluar') {
                    $barang->stok -= $transaksi->jumlah_barang;
                }

                $barang->save();
            }
        });
    }
}
