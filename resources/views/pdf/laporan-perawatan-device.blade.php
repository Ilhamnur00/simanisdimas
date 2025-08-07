<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perawatan Device</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            margin: 25px 40px;
            color: #333;
            line-height: 1.4;
        }

        .page-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px solid #2c5282;
            padding: 20px 0;
            margin-bottom: 25px;
            background-color: #f7fafc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 30px;
        }

        .logo-container img {
            width: 70px;
            height: auto;
        }

        .title {
            text-align: center;
        }

        .title h1 {
            font-size: 18pt;
            margin: 0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .title h2 {
            font-size: 14pt;
            margin-top: 4px;
            font-weight: 500;
        }

        .report-title {
            text-align: center;
            font-size: 18pt;
            font-weight: 600;
            margin: 25px 0;
            color: #2c5282;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            letter-spacing: 0.5px;
        }

        .meta-container {
            display: table;
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            padding: 12px;
            margin: 20px 0;
        }

        .meta-row {
            display: table-row;
        }

        .meta-label, .meta-value {
            display: table-cell;
            padding: 6px 10px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: bold;
            width: 100px;
            color: #2d3748;
        }

        .meta-value {
            font-weight: 600;
            color: #1a202c;
        }

        .table-container {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        thead {
            background: #2c5282;
            color: white;
            position: sticky;
            top: 0;
            box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .italic {
            font-style: italic;
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #4a5568;
            border-top: 1px solid #e2e8f0;
            padding: 15px 0;
            font-family: 'Courier New', monospace;
        }

        .footer strong {
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="page-container">

        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo-kominfo.png') }}" alt="Logo Kominfo">
                <div class="title">
                    <h1>DINAS KOMUNIKASI DAN INFORMATIKA</h1>
                    <h2>KABUPATEN BANYUMAS</h2>
                </div>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="report-title">LAPORAN PERAWATAN DEVICE</div>

        <!-- Info Periode dan Pengguna -->
        <div class="meta-container">
            <div class="meta-row">
                <div class="meta-label">Periode:</div>
                <div class="meta-value">{{ $periode }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-label">Pengguna:</div>
                <div class="meta-value">{{ $user }}</div>
            </div>
        </div>

        <!-- Tabel Perawatan -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama User</th>
                        <th>Nama Device</th>
                        <th>Kategori Perawatan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Perawatan</th>
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
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center italic">Tidak ada data perawatan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
        </div>

    </div>
</body>
</html>
