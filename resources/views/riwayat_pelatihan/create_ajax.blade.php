<form action="{{ url('/riwayat_pelatihan/ajax') }}" method="POST" id="form-tambah-riwayat-pelatihan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Riwayat Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">

                <div class="form-group" id="form-group-pengguna" style="display: none;">
                    <label>Pengguna</label>
                    <input type="hidden" name="id_pengguna" id="id_pengguna" value="{{ $user->id_pengguna }}">
                    <input type="text" class="form-control" value="{{ $user->dosen?->nama_lengkap ?? $user->tendik?->nama_lengkap }}" readonly>
                </div>
                
                <div class="form-group" id="dropdown-pengguna" style="display: {{ in_array($user->id_jenis_pengguna, [2, 3]) ? 'none' : 'block' }}">
                    <label>Pengguna</label>
                    <select name="id_pengguna" id="id_pengguna_dropdown" class="form-control">
                        <option value="">- Pilih Pengguna -</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}">
                                {{ $p->dosen?->nama_lengkap ?? $p->tendik?->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nama Pelatihan</label>
                    <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" required>
                    <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Level Pelatihan</label>
                    <select name="level_pelatihan" id="level_pelatihan" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                    <small id="error-level_pelatihan" class="error-text form-text text-danger"></small>
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
                    <label>Periode</label>
                    <select name="id_periode" id="id_periode" class="form-control">
                        <option value="">- Pilih Periode -</option>
                        @foreach($periode as $pr)
                            <option value="{{ $pr->id_periode }}">{{ $pr->tahun_periode }}</option>
                        @endforeach
                    </select>
                    <small id="error-periode" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control">
                    <small id="error-lokasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Penyelenggara</label>
                    <select name="penyelenggara" id="penyelenggara" class="form-control">
                        <option value="">- Pilih Penyelenggara -</option>
                        @foreach($penyelenggara as $vendor)
                            <option value="{{ $vendor->id_vendor_pelatihan }}">{{ $vendor->nama }}</option>
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

    // Custom file validation (not an image)
    $('#dokumen_pelatihan').on('change', function() {
        const file = this.files[0];
        if (file) {
            const validTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/png',
                'image/jpg'
            ];
            if (!validTypes.includes(file.type) || file.size > 2048000) {
                $('#error-dokumen_pelatihan').text('File harus berupa PDF, DOC, DOCX, JPEG, atau PNG dan maksimal 2MB');
                $(this).val('');
            } else {
                $('#error-dokumen_pelatihan').text('');
            }
        }
    });

    $("#form-tambah-riwayat-pelatihan").validate({
        rules: {
            id_periode: { required: true },
            nama_pelatihan: { required: true, minlength: 3, maxlength: 100 },
            level_pelatihan: { required: true },
            tanggal_mulai: { required: true },
            tanggal_selesai: { required: true },
            lokasi: { maxlength: 100 },
            penyelenggara: { maxlength: 100 },
        },
        messages: {
            nama_pelatihan: {
                required: "Nama pelatihan wajib diisi.",
                minlength: "Nama pelatihan harus memiliki setidaknya 3 karakter.",
                maxlength: "Nama pelatihan maksimal 100 karakter."
            },
            level_pelatihan: {
                required: "Level pelatihan wajib dipilih."
            },
            tanggal_mulai: {
                required: "Tanggal mulai wajib diisi."
            },
            tanggal_selesai: {
                required: "Tanggal selesai wajib diisi."
            },
            lokasi: {
                maxlength: "Lokasi maksimal 100 karakter."
            },
            penyelenggara: {
                maxlength: "Nama penyelenggara maksimal 100 karakter."
            },
            id_periode: {
                required: "Periode wajib dipilih."
            },
            mk_list: {
                required: "Minimal satu mata kuliah harus dipilih."
            },
            bidang_minat_list: {
                required: "Minimal satu bidang minat harus dipilih."
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
                        tableRiwayatPelatihan.ajax.reload();
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
