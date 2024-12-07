<!DOCTYPE html>
<html>
<head>
    <title>Export Pengajuan Pelatihan</title>
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
    <h1>Daftar Pengajuan Pelatihan</h1>
    <table>
        <thead>
            <tr>
                <th>ID Pengajuan</th>
                <th>ID Pelatihan</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>ID Peserta</th>
                <th>Nama Pelatihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan_pelatihan as $item)
                <tr>
                    <td>{{ $item->id_pengajuan }}</td>
                    <td>{{ $item->id_pelatihan }}</td>
                    <td>{{ $item->tanggal_pengajuan }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->catatan }}</td>
                    <td>{{ $item->id_peserta }}</td>
                    <td>{{ $item->daftarPelatihan->nama_pelatihan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>