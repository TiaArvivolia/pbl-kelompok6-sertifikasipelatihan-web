<form action="{{ url('/pimpinan/ajax') }}" method="POST" id="form-tambah-pimpinan" enctype="multipart/form-data">
    @csrf
    <div id="modal-pimpinan" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pimpinan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>NIDN</label>
                    <input type="text" name="nidn" id="nidn" class="form-control">
                    <small id="error-nidn" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" required>
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="input-group-append">
                            <button type="button" id="toggle-password" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <div class="input-group-append">
                            <button type="button" id="toggle-password-confirmation" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <small id="error-password_confirmation" class="error-text form-text text-danger"></small>
                </div>   

                <div class="form-group">
                    <label>Gambar Profil</label>
                    <input type="file" name="gambar_profil" id="gambar_profil" class="form-control">
                    <small id="error-gambar_profil" class="error-text form-text text-danger"></small>
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
        // Toggle visibility for password field
        $('#toggle-password').click(function() {
            var passwordField = $('#password');
            var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
        });

        // Toggle visibility for password confirmation field
        $('#toggle-password-confirmation').click(function() {
            var passwordConfirmationField = $('#password_confirmation');
            var type = passwordConfirmationField.attr('type') === 'password' ? 'text' : 'password';
            passwordConfirmationField.attr('type', type);
        });

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

        $("#form-tambah-pimpinan").validate({
            rules: {
                id_pengguna: { required: true },
                nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
                nip: { required: true, minlength: 5 },
                nidn: { required: true, minlength: 5 },
                no_telepon: { required: true, minlength: 10, maxlength: 15 },
                email: { required: true, email: true },
                username: { required: true, minlength: 3, maxlength: 50 },
                password: { required: true, minlength: 8 },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"  // Memastikan konfirmasi password sesuai dengan password
                }
            },
            messages: {
                id_pengguna: {
                    required: "ID Pengguna harus diisi."
                },
                nama_lengkap: {
                    required: "Nama Lengkap harus diisi.",
                    minlength: "Nama Lengkap harus minimal 3 karakter.",
                    maxlength: "Nama Lengkap tidak boleh lebih dari 100 karakter."
                },
                nip: {
                    required: "NIP harus diisi.",
                    minlength: "NIP harus minimal 5 karakter."
                },
                nidn: {
                    required: "NIDN harus diisi.",
                    minlength: "NIDN harus minimal 5 karakter."
                },
                no_telepon: {
                    required: "Nomor Telepon harus diisi.",
                    minlength: "Nomor Telepon harus minimal 10 karakter.",
                    maxlength: "Nomor Telepon tidak boleh lebih dari 15 karakter."
                },
                email: {
                    required: "Email harus diisi.",
                    email: "Format email tidak valid."
                },
                username: {
                    required: "Username harus diisi.",
                    minlength: "Username harus minimal 3 karakter.",
                    maxlength: "Username tidak boleh lebih dari 50 karakter."
                },
                password: {
                    required: "Password harus diisi.",
                    minlength: "Password harus minimal 8 karakter."
                },
                password_confirmation: {
                    required: "Konfirmasi Password harus diisi.",
                    equalTo: "Konfirmasi Password tidak sesuai dengan Password."
                }
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
                            // Optionally reload the data table or perform additional actions
                            tablePimpinan.ajax.reload();
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
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Silakan coba lagi nanti.'
                        });
                    }
                });
                return false; // Prevent the default form submission
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
