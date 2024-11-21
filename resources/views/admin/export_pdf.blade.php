<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1>Daftar Admin</h1>
    <table>
        <thead>
            <tr>
                <th>ID Admin</th>
                <th>Nama Lengkap</th>
                <th>NIP</th>
                <th>No Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->id_admin }}</td>
                    <td>{{ $admin->nama_lengkap }}</td>
                    <td>{{ $admin->nip }}</td>
                    <td>{{ $admin->no_telepon }}</td>
                    <td>{{ $admin->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
