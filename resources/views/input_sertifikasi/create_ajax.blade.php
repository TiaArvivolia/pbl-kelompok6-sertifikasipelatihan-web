<form action="{{ url('/input_sertifikasi/ajax') }}" method="POST" id="form-create">
@csrf
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Riwayat Sertifikasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="formCreateSertifikasi" action="{{ url('riwayat_sertifikasi/store_ajax') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="id_dosen">ID Dosen</label>
                    <input type="text" class="form-control" name="id_dosen" id="id_dosen" required>
                </div>
                
                <div class="form-group">
                    <label for="id_sertifikasi">ID Sertifikasi</label>
                    <input type="text" class="form-control" name="id_sertifikasi" id="id_sertifikasi" required>
                </div>

                <div class="form-group">
                    <label for="nama_sertifikasi">Nama Sertifikasi</label>
                    <input type="text" class="form-control" name="nama_sertifikasi" id="nama_sertifikasi" required>
                </div>

                <div class="form-group">
                    <label for="no_sertifikat">No Sertifikat</label>
                    <input type="text" class="form-control" name="no_sertifikat" id="no_sertifikat" required>
                </div>

                <div class="form-group">
                    <label for="jenis_sertifikasi">Jenis Sertifikasi</label>
                    <input type="text" class="form-control" name="jenis_sertifikasi" id="jenis_sertifikasi" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_terbit">Tanggal Terbit</label>
                    <input type="date" class="form-control" name="tanggal_terbit" id="tanggal_terbit" required>
                </div>

                <div class="form-group">
                    <label for="masa_berlaku">Masa Berlaku</label>
                    <input type="date" class="form-control" name="masa_berlaku" id="masa_berlaku">
                </div>

                <div class="form-group">
                    <label for="penyelenggara">Penyelenggara</label>
                    <input type="text" class="form-control" name="penyelenggara" id="penyelenggara" required>
                </div>

                <div class="form-group">
                    <label for="dokumen_sertifikat">Dokumen Sertifikat</label>
                    <input type="file" class="form-control" name="dokumen_sertifikat" id="dokumen_sertifikat" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="form-group">
                    <label for="diselenggarakan_oleh">Diselenggarakan Oleh</label>
                    <input type="text" class="form-control" name="diselenggarakan_oleh" id="diselenggarakan_oleh">
                </div>

                <div class="form-group">
                    <label for="tag_mk">Tag MK</label>
                    <input type="text" class="form-control" name="tag_mk" id="tag_mk">
                </div>

                <div class="form-group">
                    <label for="tag_bidang_minat">Tag Bidang Minat</label>
                    <input type="text" class="form-control" name="tag_bidang_minat" id="tag_bidang_minat">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    $('#formCreateSertifikasi').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#myModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Riwayat sertifikasi berhasil ditambahkan.',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('#table_riwayat_sertifikasi').DataTable().ajax.reload();
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    });
</script>
@endpush
