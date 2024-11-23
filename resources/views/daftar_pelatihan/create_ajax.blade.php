<form action="{{ url('/daftar_pelatihan/ajax') }}" method="POST" id="form-tambah-pelatihan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Level Pelatihan</label>
                    <select name="level_pelatihan" id="level_pelatihan" class="form-control" required>
                        <option value="">- Pilih Level Pelatihan -</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                    <small id="error-level_pelatihan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Pelatihan</label>
                    <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                    <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kuota</label>
                    <input type="number" name="kuota" id="kuota" class="form-control">
                    <small id="error-kuota" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control">
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Biaya</label>
                    <input type="text" name="biaya" id="biaya" class="form-control">
                    <small id="error-biaya" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jumlah Jam</label>
                    <input type="number" name="jml_jam" id="jml_jam" class="form-control">
                    <small id="error-jml_jam" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Vendor Pelatihan</label>
                    <select name="id_vendor_pelatihan" id="id_vendor_pelatihan" class="form-control" required>
                        <option value="">- Pilih Vendor Pelatihan -</option>
                        @foreach($vendorPelatihan as $vendor)
                            <option value="{{ $vendor->id_vendor_pelatihan }}">{{ $vendor->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_vendor_pelatihan" class="error-text form-text text-danger"></small>
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
        $("#form-tambah-pelatihan").validate({
            rules: {
                level_pelatihan: { required: true },
                nama_pelatihan: { required: true, minlength: 3, maxlength: 100 },
                tanggal_mulai: { date: true },
                tanggal_selesai: { date: true },
                kuota: { number: true },
                lokasi: { maxlength: 100 },
                biaya: { number: true },
                jml_jam: { number: true },
                id_vendor_pelatihan: { required: true },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            // Optionally reload the data table
                            tablePelatihan.ajax.reload();
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
                return false; // Prevent the default form submission
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('.form-group'));
            }
        });
    });
</script>
