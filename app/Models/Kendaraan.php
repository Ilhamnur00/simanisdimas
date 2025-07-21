<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai konvensi Laravel)
    protected $table = 'kendaraans';

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'jenis_transaksi',
        'tanggal',
        'jenis_kendaraan',
        'nama_kendaraan',
        'pengguna',
        'status_pajak',
        'perawatan',
        'bulan',
    ];

    // Jika ingin menonaktifkan kolom created_at dan updated_at, bisa tambahkan ini:
    // public $timestamps = false;

    // (Opsional) Jika kamu nanti pakai relasi ke tabel lain, bisa tambah relasi di sini
    // Contoh: kendaraan punya banyak transaksi
    // public function transaksi()
    // {
    //     return $this->hasMany(TransaksiKendaraan::class);
    // }
}
