@empty($dosen)
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
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/dosen/' . $dosen->id_dosen . '/delete_ajax') }}" method="POST" id="form-delete-dosen">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda ingin menghapus data berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Dosen :</th>
                        <td class="col-9">{{ $dosen->id_dosen }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">ID Pengguna :</th>
                        <td class="col-9">{{ $dosen->pengguna->id_pengguna }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username Pengguna :</th>
                        <td class="col-9">{{ $dosen->pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap :</th>
                        <td class="col-9">{{ $dosen->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $dosen->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIDN :</th>
                        <td class="col-9">{{ $dosen->nidn }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon :</th>
                        <td class="col-9">{{ $dosen->no_telepon }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $dosen->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah :</th>
                        <td class="col-9">{{ $dosen->mataKuliah ? $dosen->mataKuliah->nama_mk : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang Minat :</th>
                        <td class="col-9">{{ $dosen->bidangMinat ? $dosen->bidangMinat->nama_bidang_minat : '-' }}</td>
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
    $("#form-delete-dosen").validate({
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
                        tableDosen.ajax.reload(); // Pastikan ini sesuai dengan nama datatable Anda
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
