<form action="{{ url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/update_ajax') }}" method="POST" id="form-edit-pelatihan">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Pelatihan</h5>
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
                    <label>Kuota</label>
                    <input value="{{ $pelatihan->kuota }}" type="number" name="kuota" id="kuota" class="form-control">
                    <small id="error-kuota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input value="{{ $pelatihan->lokasi }}" type="text" name="lokasi" id="lokasi" class="form-control">
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Biaya</label>
                    <input value="{{ $pelatihan->biaya }}" type="number" name="biaya" id="biaya" class="form-control">
                    <small id="error-biaya" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah Jam</label>
                    <input value="{{ $pelatihan->jml_jam }}" type="number" name="jml_jam" id="jml_jam" class="form-control">
                    <small id="error-jml_jam" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Vendor Pelatihan</label>
                    <select name="id_vendor_pelatihan" id="id_vendor_pelatihan" class="form-control" required>
                        <option value="">Pilih Vendor</option>
                        @foreach($vendorPelatihan as $vendor)
                            <option value="{{ $vendor->id_vendor_pelatihan }}" 
                                {{ $pelatihan->id_vendor_pelatihan == $vendor->id_vendor_pelatihan ? 'selected' : '' }}>
                                {{ $vendor->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_vendor_pelatihan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Mata Kuliah</label>
                    <select name="tag_mk" id="tag_mk" class="form-control">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}" 
                                {{ $pelatihan->tag_mk == $mk->id_mata_kuliah ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tag_mk" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang Minat</label>
                    <select name="tag_bidang_minat" id="tag_bidang_minat" class="form-control">
                        <option value="">Pilih Bidang Minat</option>
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}" 
                                {{ $pelatihan->tag_bidang_minat == $bm->id_bidang_minat ? 'selected' : '' }}>
                                {{ $bm->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tag_bidang_minat" class="error-text form-text text-danger"></small>
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
    $("#form-edit-pelatihan").validate({
        rules: {
            nama_pelatihan: { required: true, maxlength: 100 },
            level_pelatihan: { required: true },
            tanggal_mulai: { required: false },
            tanggal_selesai: { required: false },
            kuota: { required: false, number: true },
            lokasi: { required: false, maxlength: 100 },
            biaya: { required: false, number: true },
            jml_jam: { required: false, number: true },
            id_vendor_pelatihan: { required: true },
            tag_mk: { required: false },
            tag_bidang_minat: { required: false }
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
                        tablePelatihan.ajax.reload(); // This reloads the datatable
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        }
    });
});
</script>
