<form action="{{ url('/dosen/ajax') }}" method="POST" id="form-tambah-dosen" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dosen</h5>
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
                    <input type="text" name="nidn" id="nidn" class="form-control" required>
                    <small id="error-nidn" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                    <small id="error-tempat_lahir" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                    <small id="error-tanggal_lahir" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control">
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control">
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

                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="mk_list[]" id="mk_list" class="form-control" multiple required>
                        <option value="">- Pilih Mata Kuliah -</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}">{{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                    <small id="error-mk_list" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Bidang Minat</label>
                    <select name="bidang_minat_list[]" id="bidang_minat_list" class="form-control" multiple required>
                        <option value="">- Pilih Bidang Minat -</option>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}">{{ $bm->nama_bidang_minat }}</option>
                        @endforeach
                    </select>
                    <small id="error-bidang_minat_list" class="error-text form-text text-danger"></small>
                </div>                

                {{-- <div class="form-group">
                    <label>Tag Mata Kuliah</label>
                    <select name="tag_mk" id="tag_mk" class="form-control">
                        <option value="">- Pilih Mata Kuliah -</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}">{{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                    <small id="error-tag_mk" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tag Bidang Minat</label>
                    <select name="tag_bidang_minat" id="tag_bidang_minat" class="form-control">
                        <option value="">- Pilih Bidang Minat -</option>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}">{{ $bm->nama_bidang_minat }}</option>
                        @endforeach
                    </select>
                    <small id="error-tag_bidang_minat" class="error-text form-text text-danger"></small>
                </div> --}}

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
    // Initialize select2 for the mata kuliah (mk_list) field
    $('#mk_list').select2({
        width: '100%', // Full width
        allowClear: true
    });

    // Initialize select2 for the bidang minat (bidang_minat_list) field
    $('#bidang_minat_list').select2({
        width: '100%', // Full width
        allowClear: true
    });
    // Toggle visibility for password field
    $('#toggle-password').click(function() {
        var passwordField = $('#password');
        var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
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

    // Toggle visibility for password confirmation field
    $('#toggle-password-confirmation').click(function() {
        var passwordConfirmationField = $('#password_confirmation');
        var type = passwordConfirmationField.attr('type') === 'password' ? 'text' : 'password';
        passwordConfirmationField.attr('type', type);
    });
    $("#form-tambah-dosen").validate({
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
            },
            mk_list: { required: true, maxlength: 100 },
            bidang_minat_list: { required: true, maxlength: 100 },
        },
        messages: {
            id_pengguna: {
                required: "ID Pengguna wajib diisi."
            },
            nama_lengkap: {
                required: "Nama Lengkap wajib diisi.",
                minlength: "Nama Lengkap minimal terdiri dari 3 karakter.",
                maxlength: "Nama Lengkap maksimal terdiri dari 100 karakter."
            },
            nip: {
                required: "NIP wajib diisi.",
                minlength: "NIP minimal terdiri dari 5 karakter."
            },
            nidn: {
                required: "NIDN wajib diisi.",
                minlength: "NIDN minimal terdiri dari 5 karakter."
            },
            no_telepon: {
                required: "No Telepon wajib diisi.",
                minlength: "No Telepon minimal terdiri dari 10 karakter.",
                maxlength: "No Telepon maksimal terdiri dari 15 karakter."
            },
            email: {
                required: "Email wajib diisi.",
                email: "Masukkan format email yang benar."
            },
            username: {
                required: "Username wajib diisi.",
                minlength: "Username minimal terdiri dari 3 karakter.",
                maxlength: "Username maksimal terdiri dari 50 karakter."
            },
            password: {
                required: "Password wajib diisi.",
                minlength: "Password minimal terdiri dari 8 karakter."
            },
            password_confirmation: {
                required: "Konfirmasi Password wajib diisi.",
                equalTo: "Konfirmasi Password harus sama dengan Password."
            },
            mk_list: {
                required: "Tag Mata Kuliah wajib dipilih.",
                maxlength: "Tag Mata Kuliah maksimal terdiri dari 100 karakter."
            },
            bidang_minat_list: {
                required: "Tag Bidang Minat wajib dipilih.",
                maxlength: "Tag Bidang Minat maksimal terdiri dari 100 karakter."
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
                        tableDosen.ajax.reload();
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
