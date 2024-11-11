@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Pengguna</h3>
    </div>
    <div class="card-body">
        <form id="form-create" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="peran">Peran</label>
                <select class="form-control" id="peran" name="peran" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="manager">Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label for="photo_profile">Photo Profile</label>
                <input type="file" class="form-control" id="photo_profile" name="photo_profile">
            </div>
            <!-- Tambahkan field lainnya sesuai kebutuhan -->
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kelola-pengguna.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#form-create').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('kelola-pengguna.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengguna berhasil ditambahkan!',
                    }).then(() => {
                        window.location.href = "{{ route('kelola-pengguna.index') }}";
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal menambahkan pengguna!',
                    });
                }
            });
        });
    });
</script>
@endpush
