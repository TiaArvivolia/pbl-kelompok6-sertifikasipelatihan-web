<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tendik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Data Tendik</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Tendik</th>
                <th>ID Pengguna</th>
                <th>Nama Lengkap</th>
                <th>NIP</th>
                <th>No Telepon</th>
                <th>Email</th>
                <th>Tag Bidang Minat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tendik as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_tendik }}</td>
                    <td>{{ $data->id_pengguna }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->nip }}</td>
                    <td>{{ $data->no_telepon }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ optional($data->bidangMinat)->nama_bidang_minat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
