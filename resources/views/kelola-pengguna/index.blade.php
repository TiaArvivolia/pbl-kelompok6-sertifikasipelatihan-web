@extends('layouts.template') <!-- Ganti dengan layout yang Anda gunakan -->

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <!-- Filter dan Search -->
        <div>
            <input type="text" class="form-control" placeholder="Search..." style="width: 250px; display: inline-block;">
            <select class="form-control" style="display: inline-block; width: auto;">
                <option value="">Filter Jenis</option>
                <option value="admin">Admin</option>
                <option value="pengguna">Pengguna</option>
                <!-- Tambahkan jenis pengguna lainnya sesuai kebutuhan -->
            </select>
        </div>

        <!-- Tombol Tambah Pengguna -->
        <div>
            <a href="{{ url('/kelola-pengguna/create') }}" class="btn btn-primary">Tambah Pengguna</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengguna as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user['nama_lengkap'] }}</td>
                        <td>{{ $user['jenis_pengguna'] }}</td>
                        <td>
                            <a href="{{ url('/kelola-pengguna/' . $user['id']) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ url('/kelola-pengguna/' . $user['id'] . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tombol untuk Navigasi Halaman -->
    <div class="d-flex justify-content-between mt-3">
        <div>
            <span>Menampilkan 1-10 dari {{ count($pengguna) }} pengguna</span>
        </div>
        <div>
            <a href="#" class="btn btn-secondary">
                <i class="fas fa-chevron-left"></i> <!-- Ikon untuk tombol Sebelumnya -->
            </a>
            <a href="#" class="btn btn-secondary">
                <i class="fas fa-chevron-right"></i> <!-- Ikon untuk tombol Selanjutnya -->
            </a>
        </div>
    </div>
</div>
@endsection
