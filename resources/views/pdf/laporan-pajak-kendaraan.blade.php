<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pajak Kendaraan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Pajak Kendaraan</h2>
    <p><strong>Periode:</strong> {{ $periode }}</p>
    <p><strong>Pengguna:</strong> {{ $user }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Nama Kendaraan</th>
                <th>Jenis Pajak</th>
                <th>Tanggal Dibayar</th>
                <th>Tanggal Pajak Berlaku</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pajak_kendaraan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kendaraan->user->name ?? '-' }}</td>
                    <td>{{ $item->kendaraan->nama ?? '-' }}</td>
                    <td>{{ $item->jenis_pajak }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pajak)->translatedFormat('d F Y') }}</td>
                    <td>{{ $item->deskripsi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data pajak kendaraan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
