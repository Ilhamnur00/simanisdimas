<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pajak Kendaraan</title>
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

        /* Header */
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

        /* Judul Laporan */
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

        /* Meta Info */
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

        /* Tabel asli (tidak diubah strukturnya) */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
       th, td {
            border: 1px solid #cbd5e0;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #2c5282;
            color: white;
            font-weight: bold;
            text-transform: none;
        }

        /* Footer */
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
        <div class="report-title">LAPORAN PAJAK KENDARAAN</div>

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

        <!-- Tabel Pajak (ASLI) -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Nama Kendaraan</th>
                    <th>Jenis Pajak</th>
                    <th>Tanggal Dibayar</th>
                    <th>Tanggal Pajak</th>
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

        <!-- Footer -->
        <div class="footer">
            <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
        </div>

    </div>
</body>
</html>
