<!DOCTYPE html>
<html>
<head>
    <title>Data Jenis Pelatihan</title>
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
    <h2>Data Jenis Pelatihan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Jenis Pelatihan</th>
                <th>Nama Jenis Pelatihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jenis_pelatihan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_jenis_pelatihan }}</td>
                    <td>{{ $data->nama_jenis_pelatihan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
