<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perawatan Kendaraan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Perawatan Kendaraan</h2>
    <p><strong>Periode:</strong> {{ $periode }}</p>
    <p><strong>Pengguna:</strong> {{ $user }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nama Kendaraan</th>
                <th>Tanggal</th>
                <th>Kategori Perawatan</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perawatan_kendaraan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kendaraan->user->name ?? '-' }}</td>
                    <td>{{ $item->kendaraan->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->kategori_perawatan }}</td>
                    <td>{{ $item->deskripsi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data perawatan kendaraan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
