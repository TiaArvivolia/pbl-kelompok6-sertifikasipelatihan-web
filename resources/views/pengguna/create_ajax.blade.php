<form action="{{ url('/pengguna/ajax') }}" method="POST" id="form-tambah-pengguna">
    @csrf
    <div id="modal-pengguna" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" maxlength="50" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control" maxlength="20" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" maxlength="20">
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" maxlength="20">
                </div>

                <div class="form-group">
                    <label>NIDN</label>
                    <input type="text" name="nidn" id="nidn" class="form-control" maxlength="20">
                </div>

                <div class="form-group">
                    <label>Agama</label>
                    <input type="text" name="agama" id="agama" class="form-control">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Peran</label>
                    <select name="peran" id="peran" class="form-control" required>
                        <option value="Admin">Admin</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Pimpinan">Pimpinan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" name="photo_profile" id="photo_profile" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
 $(document).ready(function() {
    $("#form-tambah-pengguna").validate({
        rules: {
            username: { required: true, maxlength: 50 },
            password: { required: true, minlength: 8 },
            nama_lengkap: { required: true, maxlength: 100 },
            nip: { required: true, maxlength: 20 },
            email: { required: true, email: true },
            jenis_kelamin: { required: true },
            peran: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
    if (response.status) {
        $('#modal-pengguna').modal('hide');
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: response.message
        });
        
        // Reload DataTable untuk menampilkan data baru
        dataPengguna.ajax.reload();
        $('#table_pengguna').DataTable().ajax.reload(null, false); // Pass false to keep the current paging
    } else {
        $('.error-text').text('');
        if (response.msgField) {
            $.each(response.msgField, function(prefix, val) {
                $('#error-' + prefix).text(val[0]);
            });
        }
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: response.message || 'Ada masalah saat menyimpan data.'
        });
    }
}

            });
            return false; // Prevent default form submission
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

