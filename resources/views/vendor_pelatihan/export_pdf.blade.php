<!DOCTYPE html>
<html>
<head>
    <title>Data Vendor Pelatihan</title>
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
    <h2>Data Vendor Pelatihan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Vendor Pelatihan</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>No Telepon</th>
                <th>Website</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendors as $index => $vendor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vendor->id_vendor_pelatihan }}</td>
                    <td>{{ $vendor->nama }}</td>
                    <td>{{ $vendor->alamat }}</td>
                    <td>{{ $vendor->kota }}</td>
                    <td>{{ $vendor->no_telepon }}</td>
                    <td>{{ $vendor->website }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
