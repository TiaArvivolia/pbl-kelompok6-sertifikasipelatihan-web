<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Riwayat Pelatihan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

    <h1>Data Riwayat Pelatihan</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pengguna</th>
                <th>Level Pelatihan</th>
                <th>Nama Pelatihan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Lokasi</th>
                <th>Penyelenggara</th>
                <th>Dokumen</th>
                <th>ID Periode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayat_pelatihan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_pengguna }}</td>
                    <td>{{ $data->level_pelatihan }}</td>
                    <td>{{ $data->nama_pelatihan }}</td>
                    <td>{{ $data->tanggal_mulai }}</td>
                    <td>{{ $data->tanggal_selesai }}</td>
                    <td>{{ $data->lokasi }}</td>
                    <td>{{ $data->penyelenggara }}</td>
                    <td>{{ $data->dokumen_pelatihan }}</td>
                    <td>{{ $data->id_periode }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('Y-m-d H:i:s') }}</p>
    </div>

</body>
</html>