<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Riwayat Sertifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Data Riwayat Sertifikasi</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Level Sertifikasi</th>
                <th>Nama Sertifikasi</th>
                <th>Tanggal Terbit</th>
                <th>Masa Berlaku</th>
                <th>Penyelenggara</th>
                <th>Dokumen Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayat_sertifikasi as $index => $sertifikasi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sertifikasi->pengguna->dosen ? $sertifikasi->pengguna->dosen->nama_lengkap : ($sertifikasi->pengguna->tendik ? $sertifikasi->pengguna->tendik->nama_lengkap : 'Tidak Tersedia') }}</td>
                    <td>{{ $sertifikasi->level_sertifikasi }}</td>
                    <td>{{ $sertifikasi->nama_sertifikasi }}</td>
                    <td>{{ \Carbon\Carbon::parse($sertifikasi->tanggal_terbit)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sertifikasi->masa_berlaku)->format('d-m-Y') }}</td>
                    <td>{{ $sertifikasi->penyelenggara }}</td>
                    <td>{{ $sertifikasi->dokumen_sertifikat ? Storage::url($sertifikasi->dokumen_sertifikat) : 'Tidak Tersedia' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

</body>
</html>