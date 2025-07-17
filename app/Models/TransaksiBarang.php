<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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
        'status_asal', // TKDN / PDN / IMPOR
        'nilai_tkdn',  // Nullable, hanya jika TKDN
    ];

    protected static function booted(): void
    {
        // Otomatis isi user_id & hitung total harga saat membuat
        static::creating(function ($transaksi) {
            if (Auth::check()) {
                $transaksi->user_id = Auth::id();
            }

            // Default tanggal hari ini jika kosong
            if (empty($transaksi->tanggal)) {
                $transaksi->tanggal = Carbon::now();
            }

            // Hitung total harga hanya untuk transaksi masuk
            if ($transaksi->jenis_transaksi === 'masuk') {
                $transaksi->total_harga = $transaksi->jumlah_barang * $transaksi->harga_satuan;
            }

            // Tambah stok langsung jika status langsung 'approved'
            if ($transaksi->status === 'approved') {
                $barang = $transaksi->barang;

                if ($transaksi->jenis_transaksi === 'masuk') {
                    $barang->stok += $transaksi->jumlah_barang;
                } elseif ($transaksi->jenis_transaksi === 'keluar') {
                    $barang->stok -= $transaksi->jumlah_barang;
                }

                $barang->save();
            }
        });

        // Update stok jika status diubah ke 'approved'
        static::updating(function ($transaksi) {
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
