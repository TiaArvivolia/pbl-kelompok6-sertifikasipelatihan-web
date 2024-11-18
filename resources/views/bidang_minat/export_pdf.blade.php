<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bidang Minat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Laporan Bidang Minat</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Bidang Minat</th>
                <th>Nama Bidang Minat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bidangMinat as $index => $bm)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bm->kode_bidang_minat }}</td>
                    <td>{{ $bm->nama_bidang_minat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
