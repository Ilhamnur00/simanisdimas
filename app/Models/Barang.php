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
     * Boot: Otomatis buat kode_barang berdasarkan kategori saat create
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

    public function getStokAttribute(): int
    {
        return $this->detailBarang()->sum('jumlah');
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

    public function semuaTransaksi()
    {
        return $this->hasMany(TransaksiBarang::class);
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiBarang::class)
                    ->where('jenis_transaksi', 'masuk');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiBarang::class)
                    ->where('jenis_transaksi', 'keluar');
    }

    public function getTotalKeluarAttribute(): int
    {
        return $this->transaksiKeluar()->sum('jumlah_barang');
    }

    /**
     * Relasi ke transaksi barang (lewat detail barang)
     */
    public function transaksiBarang()
    {
        return $this->hasManyThrough(
            TransaksiBarang::class,
            DetailBarang::class,
            'barang_id',         // Foreign key di detail_barang
            'detail_barang_id',  // Foreign key di transaksi_barang
            'id',                // Local key di barang
            'id'                 // Local key di detail_barang
        );
    }

    /**
     * Pengurangan stok (FIFO): digunakan saat transaksi keluar
     */
    public function keluarkanStok(int $jumlah): void
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
