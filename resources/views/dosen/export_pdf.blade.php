<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-left: 10px; /* Adjusted to move content to the left */
            margin-right: 10px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 0; /* Align the table more to the left */
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
    <h1>Data Dosen</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Dosen</th>
                <th>ID Pengguna</th>
                <th>Nama Lengkap</th>
                <th>NIP</th>
                <th>NIDN</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>No Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosen as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_dosen }}</td>
                    <td>{{ $data->id_pengguna }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->nip }}</td>
                    <td>{{ $data->nidn }}</td>
                    <td>{{ $data->tempat_lahir }}</td>
                    <td>{{ $data->tanggal_lahir }}</td>
                    <td>{{ $data->no_telepon }}</td>
                    <td>{{ $data->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
