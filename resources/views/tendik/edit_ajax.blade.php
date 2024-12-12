@empty($tendik)
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
                <a href="{{ url('/tendik') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/tendik/' . $tendik->id_tendik . '/update_ajax') }}" method="POST" id="form-edit-tendik" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Tendik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input value="{{ $tendik->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $tendik->nip }}" type="text" name="nip" id="nip" class="form-control" required maxlength="20">
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input value="{{ $tendik->no_telepon }}" type="text" name="no_telepon" id="no_telepon" class="form-control" maxlength="20">
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="{{ $tendik->email }}" type="email" name="email" id="email" class="form-control">
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang Minat</label>
                    <select name="tag_bidang_minat" id="tag_bidang_minat" class="form-control">
                        <option value="">Pilih Bidang Minat</option>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}" 
                                {{ $tendik->bidangMinat && $tendik->bidangMinat->id_bidang_minat == $bm->id_bidang_minat ? 'selected' : '' }}>
                                {{ $bm->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tag_bidang_minat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $tendik->pengguna->username }}" type="text" name="username" id="username" class="form-control" maxlength="50">
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
                        @if($tendik->gambar_profil)
                            <img src="{{ asset('storage/' . $tendik->gambar_profil) }}" alt="Gambar Profil" width="150" height="150" class="img-thumbnail">
                        @endif
                    </div>
                    <input type="file" name="gambar_profil" id="gambar_profil" class="form-control">
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
    $("#form-edit-tendik").validate({
        rules: {
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, maxlength: 20 },
            no_telepon: { maxlength: 20 },
            email: { email: true },
            tag_bidang_minat: { required: true },
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
                        tableTendik.ajax.reload(); // This reloads the datatable
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
