<!DOCTYPE html>
<html>
<head>
    <title>Data Jenis Pengguna</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Data Jenis Pengguna</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Jenis Pengguna</th>
                <th>Nama Jenis Pengguna</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jenis_pengguna as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->kode_jenis_pengguna }}</td>
                    <td>{{ $data->nama_jenis_pengguna }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
