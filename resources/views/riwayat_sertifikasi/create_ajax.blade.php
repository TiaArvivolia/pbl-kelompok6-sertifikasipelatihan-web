<form action="{{ url('/riwayat_sertifikasi/ajax') }}" method="POST" id="form-tambah-riwayat-sertifikasi">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Riwayat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Pengguna</label>
                    <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                        <option value="">- Pilih Pengguna -</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}">
                                {{ $p->dosen?->nama_lengkap ?? $p->tendik?->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_pengguna" class="error-text form-text text-danger"></small>
                </div>                
                
                <div class="form-group">
                    <label>Level Sertifikasi</label>
                    <select name="level_sertifikasi" id="level_sertifikasi" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                    <small id="error-level_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                {{-- <div class="form-group">
                    <label>Diselenggarakan Oleh</label>
                    <select name="diselenggarakan_oleh" id="diselenggarakan_oleh" class="form-control" required>
                        <option value="">- Pilih Penyelenggara -</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="Ikut Pelatihan">Ikut Pelatihan</option>
                    </select>
                    <small id="error-diselenggarakan_oleh" class="error-text form-text text-danger"></small>
                </div> --}}
                
                <div class="form-group">
                    <label>Jenis Sertifikasi</label>
                    <select name="jenis_sertifikasi" id="jenis_sertifikasi" class="form-control" required>
                        <option value="">- Pilih Jenis -</option>
                        <option value="Profesi">Profesi</option>
                        <option value="Keahlian">Keahlian</option>
                    </select>
                    <small id="error-jenis_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Sertifikasi</label>
                    <input type="text" name="nama_sertifikasi" id="nama_sertifikasi" class="form-control" required>
                    <small id="error-nama_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>No Sertifikat</label>
                    <input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control" required>
                    <small id="error-no_sertifikat" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="form-control" required>
                    <small id="error-tanggal_terbit" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Masa Berlaku</label>
                    <input type="date" name="masa_berlaku" id="masa_berlaku" class="form-control">
                    <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                </div>

                {{-- <div class="form-group">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" required>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div> --}}

                <div class="form-group">
                    <label>Penyelenggara</label>
                    <select name="penyelenggara" id="penyelenggara" class="form-control">
                        <option value="">- Pilih Penyelenggara -</option>
                        @foreach($penyelenggara as $vendor)
                            <option value="{{ $vendor->id_vendor_sertifikasi }}">{{ $vendor->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="dokumen_sertifikat">Dokumen Sertifikat</label>
                    <input type="file" name="dokumen_sertifikat" id="dokumen_sertifikat" class="form-control">
                    <small id="error-dokumen_sertifikat" class="error-text form-text text-danger"></small>
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
                    <select name="tag_mk[]" id="tag_mk" class="form-control select2" multiple data-placeholder="- Pilih Mata Kuliah -">
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id_mata_kuliah }}">{{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                    <small id="error-tag_mk" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Tag Bidang Minat</label>
                    <select name="tag_bidang_minat[]" id="tag_bidang_minat" class="form-control select2" multiple data-placeholder="- Pilih Bidang Minat -" >
                        @foreach($bidangMinat as $bm)
                            <option value="{{ $bm->id_bidang_minat }}">{{ $bm->nama_bidang_minat }}</option>
                        @endforeach
                    </select>
                    <small id="error-tag_bidang_minat" class="error-text form-text text-danger"></small>
                </div>     --}}

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
        $('#mk_list').select2({
            width: '100%', // Full width
            allowClear: true
        });

        // Initialize select2 for the bidang minat (bidang_minat_list) field
        $('#bidang_minat_list').select2({
            width: '100%', // Full width
            allowClear: true
        });
        $("#form-tambah-riwayat-sertifikasi").validate({
            rules: {
                id_pengguna: { required: true },
                nama_sertifikasi: { required: true, minlength: 3, maxlength: 100 },
                no_sertifikat: { required: true },
                level_sertifikasi: { required: true },
                jenis_sertifikasi: { required: true },
                tanggal_terbit: { required: true },
                masa_berlaku: { required: false },
                dokumen_sertifikat: { extension: "jpg|jpeg|png|pdf|docx" }
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
                            tableRiwayatSertifikasi.ajax.reload();
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
                return false;
            }
        });
    });
</script>

