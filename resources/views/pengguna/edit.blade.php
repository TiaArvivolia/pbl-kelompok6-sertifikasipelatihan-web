@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Pengguna</h3>
    </div>
    <div class="card-body">
        <form id="form-edit" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $pengguna->username }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ $pengguna->nama_lengkap }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $pengguna->email }}" required>
            </div>
            <div class="form-group">
                <label for="peran">Peran</label>
                <select class="form-control" id="peran" name="peran" required>
                    <option value="admin" {{ $pengguna->peran == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $pengguna->peran == 'user' ? 'selected' : '' }}>User</option>
                    <option value="manager" {{ $pengguna->peran == 'manager' ? 'selected' : '' }}>Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label for="photo_profile">Photo Profile</label>
                <input type="file" class="form-control" id="photo_profile" name="photo_profile">
                @if($pengguna->photo_profile)
                    <small>Foto saat ini:</small><br>
                    <img src="{{ asset('storage/' . $pengguna->photo_profile) }}" alt="Photo Profile" width="100">
                @endif
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
        $('#form-edit').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('kelola-pengguna.update', $pengguna->id_pengguna) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT' // Menggunakan metode PUT
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengguna berhasil diperbarui!',
                    }).then(() => {
                        window.location.href = "{{ route('kelola-pengguna.index') }}";
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memperbarui pengguna!',
                    });
                }
            });
        });
    });
</script>
@endpush
