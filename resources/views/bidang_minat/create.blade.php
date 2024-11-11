@extends('layouts.template')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Bidang Minat</h3>
    </div>
    <div class="card-body">
        <form id="form-create" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="id_bidang_minat">ID Bidang Minat</label>
                <input type="text" class="form-control" id="id_bidang_minat" name="id_bidang_minat" required>
            </div>
            <div class="form-group">
                <label for="kode_bidang_minat">Kode Bidang Minat</label>
                <input type="kode_bidang_minat" class="form-control" id="kode_bidang_minat" name="kode_bidang_minat" required>
            </div>
            <div class="form-group">
                <label for="nama_bidang_minat">Nama Bidang Minat</label>
                <input type="text" class="form-control" id="nama_bidang_minat" name="nama_bidang_minat" required>
            </div>
            <!-- Tambahkan field lainnya sesuai kebutuhan -->
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('bidang_minat.index') }}" class="btn btn-secondary">Batal</a>
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
                url: "{{ route('bidang_minat.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Bidang Minat berhasil ditambahkan!',
                    }).then(() => {
                        window.location.href = "{{ route('bidang_minat.index') }}";
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
