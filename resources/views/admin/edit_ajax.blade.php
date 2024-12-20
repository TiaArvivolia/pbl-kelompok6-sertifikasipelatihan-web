@empty($admin)
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
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/admin') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/admin/' . $admin->id_admin . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input value="{{ $admin->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input value="{{ $admin->nip }}" type="text" name="nip" id="nip" class="form-control" required maxlength="18">
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="no_telepon">No Telepon</label>
                    <input value="{{ $admin->no_telepon }}" type="text" name="no_telepon" id="no_telepon" class="form-control" required maxlength="15">
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input value="{{ $admin->email }}" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input value="{{ $admin->pengguna->username }}" type="text" name="username" id="username" class="form-control" maxlength="50">
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="password">Password (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" minlength="8">
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    <small id="error-password_confirmation" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="gambar_profil">Gambar Profil</label>
                    <div>
                        @if($admin->gambar_profil)
                            <img src="{{ asset('storage/' . $admin->gambar_profil) }}" alt="Gambar Profil" width="150" height="150" class="img-thumbnail">
                        @endif
                    </div>
                    <input type="file" name="gambar_profil" id="gambar_profil" class="form-control mt-2">
                    <small id="error-gambar_profil" class="error-text form-text text-danger"></small>
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
    
    $("#form-edit").validate({
        rules: {
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, maxlength: 18 },
            no_telepon: { required: true, maxlength: 15 },
            email: { required: true, email: true },
            username: { maxlength: 50 },
            password: { minlength: 8 },
            password_confirmation: { equalTo: "#password" },
        },
        messages: {
            password_confirmation: {
                equalTo: "Password konfirmasi harus sesuai dengan password."
            },
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
                        tableAdmin.ajax.reload();
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
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty