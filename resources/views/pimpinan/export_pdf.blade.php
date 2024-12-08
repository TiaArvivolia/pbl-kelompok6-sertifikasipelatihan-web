<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Pimpinan</title>
</head>
<body>
    <h2>Data Pimpinan</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pimpinan</th>
                <th>Nama Lengkap</th>
                <th>NIP</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pimpinan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->id_pimpinan }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->nip }}</td>
                    <td>{{ $data->no_telepon }}</td>
                    <td>{{ $data->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
