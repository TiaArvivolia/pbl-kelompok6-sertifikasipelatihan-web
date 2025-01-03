<form action="{{ url('/pengajuan_pelatihan/ajax') }}" method="POST" id="form-tambah-pengajuan-pelatihan">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pengajuan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">

                <div class="form-group">
                    <input type="hidden" name="id_pelatihan" id="id_pelatihan" class="form-control" 
                    value="{{ $selectedPelatihan ? $selectedPelatihan->id_pelatihan : '' }}" readonly>
                    <label for="nama_pelatihan">Nama Pelatihan</label>
                    <input type="text" name="nama_pelatihan" id="nama_pelatihan" class="form-control" 
                           value="{{ $selectedPelatihan ? $selectedPelatihan->nama_pelatihan : '' }}" readonly>
                </div>

                <div class="form-group">
                    <label>Peserta</label>
                    <select name="id_peserta[]" id="id_peserta" class="form-control" multiple>
                        <option value="">- Pilih Peserta -</option>
                        @if($rekomendasiPeserta)
                            <optgroup label="{{ $selectedPelatihan->nama_pelatihan }}">
                                @foreach($rekomendasiPeserta as $peserta)
                                    <option value="{{ $peserta->id_pengguna }}">
                                        @if($peserta->dosen)
                                            {{ $peserta->dosen->nama_lengkap }}
                                        @elseif($peserta->tendik)
                                            {{ $peserta->tendik->nama_lengkap }}
                                        @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        @else
                            <option value="" disabled>Tidak ada peserta yang direkomendasikan</option>
                        @endif
                    </select>
                </div>
                

                {{-- <div class="form-group">
                    <label>Peserta</label>
                    <select name="id_peserta[]" id="id_peserta" class="form-control" multiple required>
                        <option value="">- Pilih Peserta -</option>
                        @foreach($pengguna as $p)
                            <option value="{{ $p->id_pengguna }}">
                                @if($p->dosen)
                                    {{ $p->dosen->nama_lengkap }}
                                @elseif($p->tendik)
                                    {{ $p->tendik->nama_lengkap }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_peserta" class="error-text form-text text-danger"></small>
                </div> --}}

                <div class="form-group">
                    <label>Tanggal Pengajuan</label>
                    <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" class="form-control" required>
                    <small id="error-tanggal_pengajuan" class="error-text form-text text-danger"></small>
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

                {{-- <div class="form-group">
                    <label>Status Pengajuan</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div> --}}

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control"></textarea>
                    <small id="error-catatan" class="error-text form-text text-danger"></small>
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
    $('#id_peserta').select2({
        width: '100%',
        allowClear: true
    });

    // Saat memilih dari rekomendasi, otomatis tambahkan ke id_peserta
    // $('#rekomendasi_peserta').on('change', function() {
    //     let selectedValues = $(this).val();
    //     if (selectedValues) {
    //         selectedValues.forEach(function(value) {
    //             let pesertaOption = $('#id_peserta option[value="' + value + '"]');
    //             if (!pesertaOption.prop('selected')) {
    //                 pesertaOption.prop('selected', true).trigger('change');
    //             }
    //         });
    //     }
    // });
    $("#form-tambah-pengajuan-pelatihan").validate({
        rules: {
            id_pelatihan: { required: true },
            tanggal_pengajuan: { required: true },
            // status: { required: true },
            id_peserta: { required: true }
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
                        tablePengajuanPelatihan.ajax.reload();
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
