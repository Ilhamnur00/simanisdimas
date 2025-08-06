<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perawatan Device</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .meta {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        table, th, td {
            border: 1px solid #999;
        }
        th, td {
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .italic {
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>Laporan Perawatan Device</h2>
    <h4>{{ $periode }}</h4>

    <div class="meta">
        <strong>Pengguna:</strong> {{ $user }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama User</th>
                <th>Nama Device</th>
                <th>Kategori Perawatan</th>
                <th>Deskripsi</th>
                <th>Bukti</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perawatan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->device->nama ?? '-' }}</td>
                    <td>{{ $item->kategori_perawatan }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>
                        @if ($item->bukti)
                            {{ asset('storage/' . $item->bukti) }}
                        @else
                            <span class="italic">-</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center italic">Tidak ada data perawatan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
