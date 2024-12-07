<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
 ```blade
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelatihan</title>
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
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Daftar Pelatihan</h1>
    <table>
        <thead>
            <tr>
                <th>ID Pelatihan</th>
                <th>Nama Pelatihan</th>
                <th>Level Pelatihan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Kuota</th>
                <th>Lokasi</th>
                <th>Biaya</th>
                <th>Jumlah Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelatihan as $data)
                <tr>
                    <td>{{ $data->id_pelatihan }}</td>
                    <td>{{ $data->nama_pelatihan }}</td>
                    <td>{{ $data->level_pelatihan }}</td>
                    <td>{{ $data->tanggal_mulai }}</td>
                    <td>{{ $data->tanggal_selesai }}</td>
                    <td>{{ $data->kuota }}</td>
                    <td>{{ $data->lokasi }}</td>
                    <td>{{ $data->biaya }}</td>
                    <td>{{ $data->jml_jam }}</td>
                </tr>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

</body>
</html>