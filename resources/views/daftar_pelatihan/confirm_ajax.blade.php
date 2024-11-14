@empty($pelatihan)
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
                <a href="{{ url('/daftar_pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/delete_ajax') }}" method="POST" id="form-delete-daftar-pelatihan">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda ingin menghapus data pelatihan berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->id_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->level_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai :</th>
                        <td class="col-9">{{ $pelatihan->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai :</th>
                        <td class="col-9">{{ $pelatihan->tanggal_selesai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota :</th>
                        <td class="col-9">{{ $pelatihan->kuota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Lokasi :</th>
                        <td class="col-9">{{ $pelatihan->lokasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Biaya :</th>
                        <td class="col-9">{{ $pelatihan->biaya }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah Jam :</th>
                        <td class="col-9">{{ $pelatihan->jml_jam }}</td>
                    </tr>
                </table>
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
    $("#form-delete-daftar-pelatihan").validate({
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
                        tablePelatihan.ajax.reload(); // Pastikan ini sesuai dengan nama datatable Anda
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
