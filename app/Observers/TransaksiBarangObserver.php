<?php

namespace App\Observers;

use App\Models\TransaksiBarang;
use App\Models\DetailBarang;
use Illuminate\Support\Facades\DB;

class TransaksiBarangObserver
{
    public function creating(TransaksiBarang $transaksi): void
    {
        $transaksi->tanggal = $transaksi->tanggal ?? now();
        $barang = $transaksi->barang;

        if (!$barang) {
            throw new \Exception('Barang tidak ditemukan.');
        }

        if ($transaksi->jenis_transaksi === 'masuk') {
            $nilaiTkdn = $transaksi->status_asal === 'TKDN'
                ? ($transaksi->nilai_tkdn ?? 0)
                : null;

            $totalHarga = ($transaksi->harga_satuan ?? 0) * $transaksi->jumlah_barang;

            $detail = DetailBarang::where('barang_id', $barang->id)
                ->where('status_asal', $transaksi->status_asal)
                ->where('harga_satuan', $transaksi->harga_satuan)
                ->when($transaksi->status_asal === 'TKDN', function ($query) use ($nilaiTkdn) {
                    return $query->where('nilai_tkdn', $nilaiTkdn);
                })
                ->first();

            if (!$detail) {
                $detail = DetailBarang::create([
                    'barang_id' => $barang->id,
                    'status_asal' => $transaksi->status_asal,
                    'nilai_tkdn' => $nilaiTkdn,
                    'harga_satuan' => $transaksi->harga_satuan,
                    'jumlah' => $transaksi->jumlah_barang,
                    'total_harga' => $totalHarga,
                    'tanggal_masuk' => $transaksi->tanggal,
                ]);
            } else {
                $detail->increment('jumlah', $transaksi->jumlah_barang);
                $detail->increment('total_harga', $totalHarga);
            }

            $transaksi->detail_barang_id = $detail->id;
            $transaksi->nilai_tkdn = $nilaiTkdn;
            $transaksi->total_harga = $totalHarga;

        } elseif ($transaksi->jenis_transaksi === 'keluar') {
            if ($barang->stok < $transaksi->jumlah_barang) {
                throw new \Exception('Stok tidak mencukupi.');
            }

            // Jalankan transaksi DB agar rollback jika gagal
            DB::beginTransaction();

            try {
                $tersisa = $transaksi->jumlah_barang;
                $originalAttributes = $transaksi->getAttributes();

                $details = $barang->detailBarang()->orderBy('id')->get();

                foreach ($details as $detail) {
                    if ($tersisa <= 0) break;

                    $ambil = min($tersisa, $detail->jumlah);
                    if ($ambil <= 0) continue;

                    $detail->decrement('jumlah', $ambil);
                    $detail->decrement('total_harga', $ambil * $detail->harga_satuan);

                    $tersisa -= $ambil;

                    if ($tersisa == 0) {
                        // Isi transaksi utama dengan detail pertama yang digunakan
                        $transaksi->detail_barang_id = $detail->id;
                        $transaksi->harga_satuan = null;
                        $transaksi->status_asal = null;
                        $transaksi->nilai_tkdn = null;
                        $transaksi->total_harga = null;
                        $transaksi->jumlah_barang = $ambil;
                        break;
                    } else {
                        // Buat transaksi tambahan (salinan)
                        TransaksiBarang::create([
                            ...$originalAttributes,
                            'jumlah_barang' => $ambil,
                            'detail_barang_id' => $detail->id,
                            'harga_satuan' => null,
                            'status_asal' => null,
                            'nilai_tkdn' => null,
                            'total_harga' => null,
                        ]);
                    }
                }

                if ($tersisa > 0) {
                    throw new \Exception('Stok tidak mencukupi saat proses FIFO.');
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}
