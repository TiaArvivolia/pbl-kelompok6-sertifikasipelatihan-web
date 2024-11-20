<!DOCTYPE html>
<html>
<head>
    <title>Data Pengguna</title>
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
    <h2>Data Pengguna</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengguna as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->username }}</td>
                    <td>{{ $data->nama_jenis_pengguna }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
