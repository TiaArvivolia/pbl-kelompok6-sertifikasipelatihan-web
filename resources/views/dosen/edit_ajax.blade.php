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
<form action="{{ url('/dosen/' . $dosen->id_dosen . '/update_ajax') }}" method="POST" id="form-edit-dosen" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input value="{{ $dosen->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $dosen->nip }}" type="text" name="nip" id="nip" class="form-control" required maxlength="18">
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIDN</label>
                    <input value="{{ $dosen->nidn }}" type="text" name="nidn" id="nidn" class="form-control" required maxlength="20">
                    <small id="error-nidn" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input value="{{ $dosen->tempat_lahir }}" type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required>
                    <small id="error-tempat_lahir" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input value="{{ $dosen->tanggal_lahir }}" type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                    <small id="error-tanggal_lahir" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input value="{{ $dosen->no_telepon }}" type="text" name="no_telepon" id="no_telepon" class="form-control" required maxlength="15">
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="{{ $dosen->email }}" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <!-- Input untuk Mata Kuliah -->
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="mk_list[]" id="mk_list" class="form-control" multiple required>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}" 
                                {{ in_array($mk->id_mata_kuliah, json_decode($dosen->mk_list ?? '[]')) ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-mk_list" class="error-text form-text text-danger"></small>
                </div>

                <!-- Input untuk Bidang Minat -->
                <div class="form-group">
                    <label>Bidang Minat</label>
                    <select name="bidang_minat_list[]" id="bidang_minat_list" class="form-control" multiple required>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}" 
                                {{ in_array($bm->id_bidang_minat, json_decode($dosen->bidang_minat_list ?? '[]')) ? 'selected' : '' }}>
                                {{ $bm->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-bidang_minat_list" class="error-text form-text text-danger"></small>
                </div>
                {{-- <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="tag_mk" id="tag_mk" class="form-control" required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}" 
                                {{ $dosen->mataKuliah && $dosen->mataKuliah->id_mata_kuliah == $mk->id_mata_kuliah ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tag_mk" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Bidang Minat</label>
                    <select name="tag_bidang_minat" id="tag_bidang_minat" class="form-control" required>
                        <option value="">Pilih Bidang Minat</option>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}" 
                                {{ $dosen->bidangMinat && $dosen->bidangMinat->id_bidang_minat == $bm->id_bidang_minat ? 'selected' : '' }}>
                                {{ $bm->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_bidang_minat" class="error-text form-text text-danger"></small>
                </div> --}}
                <div class="form-group">
                    <label>Username</label>
                    <input value="{{ $dosen->pengguna->username }}" type="text" name="username" id="username" class="form-control" maxlength="50">
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
                        @if($dosen->gambar_profil)
                            <img src="{{ asset('storage/' . $dosen->gambar_profil) }}" alt="Gambar Profil" width="150" height="150" class="img-thumbnail">
                        @endif
                    </div>
                    <input type="file" name="gambar_profil" class="form-control mt-2">
                </div>
{{--                      
                <div class="form-group">
                    <label>Gambar Profil</label>
                    <input type="file" name="gambar_profil" id="gambar_profil" class="form-control">
                    <small id="error-gambar_profil" class="error-text form-text text-danger"></small>
                </div> --}}
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
    $('#mk_list').select2({
        width: '100%', // Full width
        allowClear: true
    });

    // Initialize select2 for the bidang minat (bidang_minat_list) field
    $('#bidang_minat_list').select2({
        width: '100%', // Full width
        allowClear: true
    });
    $("#form-edit-dosen").validate({
        rules: {
            nama_lengkap: { required: true, minlength: 3, maxlength: 100 },
            nip: { required: true, maxlength: 18 },
            nidn: { required: true, maxlength: 20 },
            tempat_lahir: { required: true, maxlength: 100 },
            tanggal_lahir: { required: true },
            no_telepon: { required: true, maxlength: 15 },
            email: { required: true, email: true },
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
                        tableDosen.ajax.reload(); // This reloads the datatable
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
