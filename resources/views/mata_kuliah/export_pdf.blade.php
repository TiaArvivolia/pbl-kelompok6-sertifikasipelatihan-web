<!DOCTYPE html>
<html>
<head>
    <title>Data Mata Kuliah</title>
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
    <h2>Data Mata Kuliah</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Mata Kuliah</th>
                <th>Kode Mata Kuliah</th>
                <th>Nama Mata Kuliah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mata_kuliah as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_mata_kuliah }}</td>
                    <td>{{ $data->kode_mk }}</td>
                    <td>{{ $data->nama_mk }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
