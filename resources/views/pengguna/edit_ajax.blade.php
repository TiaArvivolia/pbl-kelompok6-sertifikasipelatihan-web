@empty($pengguna)
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
            <a href="{{ url('/pengguna') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/pengguna/' . $pengguna->id_pengguna . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $pengguna->username }}" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input value="{{ $pengguna->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $pengguna->nip }}" type="text" name="nip" id="nip" class="form-control" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input value="{{ $pengguna->tempat_lahir }}" type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input value="{{ $pengguna->tanggal_lahir }}" type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">- Pilih Jenis Kelamin -</option>
                        <option value="L" {{ ($pengguna->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ ($pengguna->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input value="{{ $pengguna->no_telepon }}" type="text" name="no_telepon" id="no_telepon" class="form-control">
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input value="{{ $pengguna->nik }}" type="text" name="nik" id="nik" class="form-control">
                </div>
                <div class="form-group">
                    <label>NIDN</label>
                    <input value="{{ $pengguna->nidn }}" type="text" name="nidn" id="nidn" class="form-control">
                </div>
                <div class="form-group">
                    <label>Agama</label>
                    <input value="{{ $pengguna->agama }}" type="text" name="agama" id="agama" class="form-control">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control">{{ $pengguna->alamat }}</textarea>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="{{ $pengguna->email }}" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Peran</label>
                    <select name="peran" id="peran" class="form-control" required>
                        <option value="">- Pilih Peran -</option>
                        <option value="Admin" {{ ($pengguna->peran == 'Admin') ? 'selected' : '' }}>Admin</option>
                        <option value="Dosen" {{ ($pengguna->peran == 'Dosen') ? 'selected' : '' }}>Dosen</option>
                        <option value="Pimpinan" {{ ($pengguna->peran == 'Pimpinan') ? 'selected' : '' }}>Pimpinan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small class="form-text text-muted">Abaikan jika tidak ingin mengubah password</small>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="photo_profile" id="photo_profile" class="form-control">
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
    $("#form-edit").validate({
        rules: {
            username: { required: true, minlength: 3, maxlength: 50 },
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, minlength: 5, maxlength: 20 },
            email: { required: true, email: true },
            password: { minlength: 6, maxlength: 20 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form), // use FormData to handle file uploads
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
                        dataPengguna.ajax.reload();
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
