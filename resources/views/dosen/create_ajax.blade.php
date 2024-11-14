<form action="{{ url('/dosen/ajax') }}" method="POST" id="form-tambah-dosen">
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
                    <label>ID Pengguna</label>
                    <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                        <option value="">- Pilih Pengguna -</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}">{{ $p->username }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_pengguna" class="error-text form-text text-danger"></small>
                </div>

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
                    <label>Gambar Profil</label>
                    <input type="file" name="gambar_profil" id="gambar_profil" class="form-control">
                    <small id="error-gambar_profil" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
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
    $("#form-tambah-dosen").validate({
        rules: {
            id_pengguna: { required: true },
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, minlength: 5 },
            nidn: { required: true, minlength: 5 },
            no_telepon: { required: true, minlength: 10, maxlength: 15 },
            email: { required: true, email: true },
            tag_mk: { required: true, maxlength: 100 },
            tag_bidang_minat: { required: true, maxlength: 100 },
            gambar_profil: { extension: "jpg|jpeg|png|gif|bmp" }
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
