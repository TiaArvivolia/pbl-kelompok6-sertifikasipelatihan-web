@empty($sertifikasi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/riwayat_sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/riwayat_sertifikasi/' . $sertifikasi->id_riwayat . '/delete_ajax') }}" method="POST" id="form-delete-riwayat-sertifikasi">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Riwayat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda ingin menghapus data berikut?
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">ID Riwayat Sertifikasi :</th>
                            <td class="col-9">{{ $sertifikasi->id_riwayat }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">ID Pengguna :</th>
                            <td class="col-9">{{ $sertifikasi->id_pengguna }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Sertifikasi :</th>
                            <td class="col-9">{{ $sertifikasi->nama_sertifikasi }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">No Sertifikat :</th>
                            <td class="col-9">{{ $sertifikasi->no_sertifikat }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Terbit :</th>
                            <td class="col-9">{{ $sertifikasi->tanggal_terbit }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Masa Berlaku :</th>
                            <td class="col-9">{{ $sertifikasi->masa_berlaku }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penyelenggara :</th>
                            <td class="col-9">{{ $sertifikasi->penyelenggara }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-delete-riwayat-sertifikasi").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tableRiwayatSertifikasi.ajax.reload(); // Pastikan ini sesuai dengan nama datatable Anda
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
