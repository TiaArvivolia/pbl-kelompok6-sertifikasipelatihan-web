<!DOCTYPE html>
<html>
<head>
    <title>Data Bidang Minat</title>
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
    <h2>Data Bidang Minat</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Bidang Minat</th>
                <th>Kode Bidang Minat</th>
                <th>Nama Bidang Minat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bidang_minat as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_bidang_minat }}</td>
                    <td>{{ $data->kode_bidang_minat }}</td>
                    <td>{{ $data->nama_bidang_minat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
