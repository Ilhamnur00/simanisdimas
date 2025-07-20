<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_barang';

    protected $fillable = [
        'user_id',
        'barang_id',
        'jumlah_barang',
        'tanggal',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
