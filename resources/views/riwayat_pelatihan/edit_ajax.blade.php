@empty($pelatihan)
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
                <a href="{{ url('/riwayat_pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/riwayat_pelatihan/' . $pelatihan->id_riwayat . '/update_ajax') }}" method="POST" id="form-edit-riwayat-pelatihan">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Riwayat Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pelatihan</label>
                    <input value="{{ $pelatihan->nama_pelatihan }}" type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                    <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Level Pelatihan</label>
                    <select name="level_pelatihan" id="level_pelatihan" class="form-control" required>
                        <option value="">Pilih Level Pelatihan</option>
                        <option value="Nasional" {{ $pelatihan->level_pelatihan == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Internasional" {{ $pelatihan->level_pelatihan == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                    <small id="error-level_pelatihan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input value="{{ $pelatihan->tanggal_mulai }}" type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input value="{{ $pelatihan->tanggal_selesai }}" type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <select name="id_periode" id="id_periode" class="form-control">
                        <option value="">Pilih Periode</option>
                        @foreach($periode as $pr)
                            <option value="{{ $pr->id_periode }}" 
                                {{ $pelatihan->id_periode == $pr->id_periode ? 'selected' : '' }}>
                                {{ $pr->tahun_periode }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input value="{{ $pelatihan->lokasi }}" type="text" name="lokasi" id="lokasi" class="form-control">
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Penyelenggara</label>
                    <select name="penyelenggara" id="penyelenggara" class="form-control">
                        <option value="" >Pilih Penyelenggara</option>
                        @foreach($penyelenggara as $p)
                            <option value="{{ $p->id_vendor_pelatihan }}" 
                                {{ $pelatihan->penyelenggara == $p->id_vendor_pelatihan ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Dokumen Pelatihan</label>
                    <input type="file" name="dokumen_pelatihan" id="dokumen_pelatihan" class="form-control">
                    <small id="error-dokumen_pelatihan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pengguna</label>
                    <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                        <option value="">Pilih Pengguna</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}" 
                                {{ $pelatihan->id_pengguna == $p->id_pengguna ? 'selected' : '' }}>
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
                                {{ in_array($mk->id_mata_kuliah, json_decode($pelatihan->mk_list ?? '[]')) ? 'selected' : '' }}>
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
                                {{ in_array($bm->id_bidang_minat, json_decode($pelatihan->bidang_minat_list ?? '[]')) ? 'selected' : '' }}>
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
    $("#form-edit-riwayat-pelatihan").validate({
        rules: {
            nama_pelatihan: { required: true, maxlength: 100 },
            level_pelatihan: { required: true },
            tanggal_mulai: { required: true },
            tanggal_selesai: { required: true },
            lokasi: { maxlength: 100 },
            penyelenggara: { maxlength: 100 },
            dokumen_pelatihan: { extension: "pdf|doc|docx|jpg|jpeg|png" },
            id_pengguna: { required: true },
            id_pelatihan: { required: true },
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
                        tableRiwayatPelatihan.ajax.reload(); // This reloads the datatable
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
