@empty($pimpinan)
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
                <a href="{{ url('/pimpinan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/pimpinan/' . $pimpinan->id_pimpinan . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Pimpinan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input value="{{ $pimpinan->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                            <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>NIP</label>
                            <input value="{{ $pimpinan->nip }}" type="text" name="nip" id="nip" class="form-control" required maxlength="18">
                            <small id="error-nip" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>NIDN</label>
                            <input value="{{ $pimpinan->nidn }}" type="text" name="nidn" id="nidn" class="form-control" maxlength="20">
                            <small id="error-nidn" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label>
                            <input value="{{ $pimpinan->no_telepon }}" type="text" name="no_telepon" id="no_telepon" class="form-control" maxlength="15">
                            <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input value="{{ $pimpinan->email }}" type="email" name="email" id="email" class="form-control">
                            <small id="error-email" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            <label>ID Pengguna</label>
                            <input value="{{ $pimpinan->id_pengguna }}" type="text" name="id_pengguna" id="id_pengguna" class="form-control" required>
                            <small id="error-id_pengguna" class="error-text form-text text-danger"></small>
                        </div> --}}
                        <div class="form-group">
                            <label>Username</label>
                            <input value="{{ $pimpinan->pengguna->username }}" type="text" name="username" id="username" class="form-control" maxlength="50">
                            <small id="error-username" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Password (Opsional)</label>
                            <input type="password" name="password" id="password" class="form-control" minlength="8">
                            <small id="error-password" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <small id="error-password_confirmation" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="gambar_profil">Gambar Profil</label>
                            <div>
                                @if($pimpinan->gambar_profil)
                                    <img src="{{ asset('storage/' . $pimpinan->gambar_profil) }}" alt="Gambar Profil" width="150" height="150" class="img-thumbnail">
                                @endif
                            </div>
                            <input type="file" name="gambar_profil" id="gambar_profil" class="form-control mt-2">
                            <small id="error-gambar_profil" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Custom file validation
    $('#gambar_profil').on('change', function() {
        const file = this.files[0];
        if (file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type) || file.size > 2048000) {
                $('#error-gambar_profil').text('File harus berupa JPG, JPEG, atau PNG dan maksimal 2MB');
                $(this).val('');
            } else {
                $('#error-gambar_profil').text('');
            }
        }
    });

    // Validation rules
    $("#form-edit").validate({
        rules: {
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, maxlength: 18 },
            nidn: { maxlength: 20 },
            no_telepon: { maxlength: 15 },
            email: { required: true, email: true },
            id_pengguna: { required: true },
            username: { required: true }, 
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tablePimpinan.ajax.reload(); // Adjust to match your datatable name
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
