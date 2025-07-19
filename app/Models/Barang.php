<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kategori_id',
        'nama_barang',
        'kode_barang',
    ];

    /**
     * Boot: Otomatis buat kode_barang berdasarkan kategori
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barang) {
            if (empty($barang->kode_barang)) {
                $prefix = $barang->kategori?->kode_kategori ?? 'UNK';
                $count = self::where('kategori_id', $barang->kategori_id)->count() + 1;
                $barang->kode_barang = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Accessor: Hitung stok dari barang masuk - barang keluar
     */
    public function getStokAttribute()
    {
        $masuk = $this->detailBarang()->sum('jumlah');

        $keluar = \App\Models\TransaksiBarang::whereHas('detailBarang', function ($query) {
            $query->where('barang_id', $this->id);
        })->where('jenis_transaksi', 'keluar')->sum('jumlah_barang');

        return $masuk - $keluar;
    }

    /**
     * Relasi ke kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke detail barang
     */
    public function detailBarang()
    {
        return $this->hasMany(DetailBarang::class);
    }

    /**
     * Relasi ke transaksi (lewat detail barang)
     * Digunakan jika ingin ambil semua transaksi barang ini
     */
    public function transaksiBarang()
    {
        return $this->hasManyThrough(
            TransaksiBarang::class,
            DetailBarang::class,
            'barang_id',         // FK di detail_barang
            'detail_barang_id',  // FK di transaksi_barang
            'id',                // PK di barang
            'id'                 // PK di detail_barang
        );
    }

    /**
     * Pengurangan stok: digunakan saat transaksi keluar
     */
    public function keluarkanStok($jumlah): void
    {
        $tersisa = $jumlah;

        foreach ($this->detailBarang()->orderBy('id')->get() as $detail) {
            if ($tersisa <= 0) break;

            $ambil = min($tersisa, $detail->jumlah);
            $detail->jumlah -= $ambil;
            $detail->save();

            $tersisa -= $ambil;
        }
    }
}