@empty($sertifikasi)
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
                <a href="{{ url('/riwayat_sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/riwayat_sertifikasi/' . $sertifikasi->id_riwayat . '/update_ajax') }}" method="POST" id="form-edit-riwayat-sertifikasi">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Riwayat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Sertifikasi</label>
                    <input value="{{ $sertifikasi->nama_sertifikasi }}" type="text" name="nama_sertifikasi" id="nama_sertifikasi" class="form-control" required>
                    <small id="error-nama_sertifikasi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Level Sertifikasi</label>
                    <select name="level_sertifikasi" id="level_sertifikasi" class="form-control" required>
                        <option value="">Pilih Level Sertifikasi</option>
                        <option value="Nasional" {{ $sertifikasi->level_sertifikasi == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Internasional" {{ $sertifikasi->level_sertifikasi == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                    <small id="error-level_sertifikasi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Terbit</label>
                    <input value="{{ $sertifikasi->tanggal_terbit }}" type="date" name="tanggal_terbit" id="tanggal_terbit" class="form-control">
                    <small id="error-tanggal_terbit" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input value="{{ $sertifikasi->masa_berlaku }}" type="date" name="masa_berlaku" id="masa_berlaku" class="form-control">
                    <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tahun Periode</label>
                    <input value="{{ $sertifikasi->tahun_periode }}" type="number" name="tahun_periode" id="tahun_periode" class="form-control">
                    <small id="error-tahun_periode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Sertifikat</label>
                    <input value="{{ $sertifikasi->no_sertifikat }}" type="text" name="no_sertifikat" id="no_sertifikat" class="form-control" required>
                    <small id="error-no_sertifikat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Sertifikasi</label>
                    <input value="{{ $sertifikasi->jenis_sertifikasi }}" type="text" name="jenis_sertifikasi" id="jenis_sertifikasi" class="form-control" required>
                    <small id="error-jenis_sertifikasi" class="error-text form-text text-danger"></small>
                </div>
                {{-- <div class="form-group">
                    <label>Penyelenggara</label>
                    <input value="{{ $sertifikasi->penyelenggara }}" type="text" name="penyelenggara" id="penyelenggara" class="form-control" required>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div> --}}
                <div class="form-group">
                    <label>Penyelenggara</label>
                    <select name="penyelenggara" id="penyelenggara" class="form-control">
                        <option value="">Pilih Penyelenggara</option>
                        @foreach($penyelenggara as $p)
                            <option value="{{ $p->id_vendor_sertifikasi }}" 
                                {{ $sertifikasi->penyelenggara == $p->id_vendor_sertifikasi ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Dokumen Sertifikat</label>
                    <input type="file" name="dokumen_sertifikat" id="dokumen_sertifikat" class="form-control">
                    <small id="error-dokumen_sertifikat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pengguna</label>
                    <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                        <option value="">Pilih Pengguna</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}" 
                                {{ $sertifikasi->id_pengguna == $p->id_pengguna ? 'selected' : '' }}>
                                @if($p->dosen)
                                    {{ $p->dosen->nama_lengkap }}
                                @elseif($p->tendik)
                                    {{ $p->tendik->nama_lengkap }}
                                @endif
                            </option>
                        @endforeach
                    </select>                                                  
                    <small id="error-id_pengguna" class="error-text form-text text-danger"></small>
                </div>
                <!-- Input untuk Mata Kuliah -->
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="mk_list[]" id="mk_list" class="form-control" multiple required>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}" 
                                {{ in_array($mk->id_mata_kuliah, json_decode($sertifikasi->mk_list ?? '[]')) ? 'selected' : '' }}>
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
                                {{ in_array($bm->id_bidang_minat, json_decode($sertifikasi->bidang_minat_list ?? '[]')) ? 'selected' : '' }}>
                                {{ $bm->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-bidang_minat_list" class="error-text form-text text-danger"></small>
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
    $('#mk_list').select2({
        width: '100%', // Full width
        allowClear: true
    });

    // Initialize select2 for the bidang minat (bidang_minat_list) field
    $('#bidang_minat_list').select2({
        width: '100%', // Full width
        allowClear: true
    });
    $("#form-edit-riwayat-sertifikasi").validate({
        rules: {
            nama_sertifikasi: { required: true, maxlength: 100 },
            level_sertifikasi: { required: true },
            tanggal_terbit: { required: true },
            masa_berlaku: { required: true },
            no_sertifikat: { required: true, maxlength: 100 },
            // diselenggarakan_oleh: { required: true },
            penyelenggara: { maxlength: 100 },
            dokumen_sertifikat: { extension: "pdf|doc|docx|jpg|jpeg|png" },
            id_pengguna: { required: true },
            tag_mk: { required: true },
            tag_bidang_minat: { required: true }
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
                        tableRiwayatSertifikasi.ajax.reload(); // This reloads the datatable
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
