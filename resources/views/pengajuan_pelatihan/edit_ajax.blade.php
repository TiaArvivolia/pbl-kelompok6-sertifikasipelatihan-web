@empty($pengajuan)
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
            <a href="{{ url('/pengajuan_pelatihan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/update_ajax') }}" method="POST" id="form-edit-pengajuan-pelatihan">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pengajuan Pelatihan</h5>
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
                {{-- <div class="form-group">
                    <label>Pelatihan</label>
                    <select name="id_pelatihan" id="id_pelatihan" class="form-control" required>
                        <option value="">Pilih Pelatihan</option>
                        @foreach($allPelatihan as $pelatihan)
                            <option value="{{ $pelatihan->id_pelatihan }}" 
                                {{ $selectedPelatihan && $selectedPelatihan->id_pelatihan == $pelatihan->id_pelatihan ? 'selected' : '' }}>
                                {{ $pelatihan->nama_pelatihan }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_pelatihan" class="error-text form-text text-danger"></small>
                </div> --}}
                
                <div class="form-group">
                    <label>Peserta</label>
                    <select name="id_peserta[]" id="id_peserta" class="form-control" multiple required>
                        {{-- Rekomendasi Peserta Berdasarkan Pelatihan --}}
                        @if(isset($rekomendasiPeserta))
                            @foreach($rekomendasiPeserta as $peserta)
                                <option value="{{ $peserta->id_pengguna }}"
                                    {{-- Check if the peserta is already selected based on the current training submission --}}
                                    {{ in_array($peserta->id_pengguna, json_decode($pengajuan->id_peserta ?? '[]')) ? 'selected' : '' }}>
                                    @if($peserta->dosen)
                                        {{ $peserta->dosen->nama_lengkap }}
                                    @elseif($peserta->tendik)
                                        {{ $peserta->tendik->nama_lengkap }}
                                    @endif
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Tidak ada peserta yang direkomendasikan</option>
                        @endif
                    </select>
                    <small id="error-id_peserta" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Pengajuan</label>
                    <input value="{{ $pengajuan->tanggal_pengajuan }}" type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" class="form-control" required>
                    <small id="error-tanggal_pengajuan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <select name="id_periode" id="id_periode" class="form-control">
                        <option value="">Pilih Periode</option>
                        @foreach($periode as $pr)
                            <option value="{{ $pr->id_periode }}" 
                                {{ $pengajuan->id_periode == $pr->id_periode ? 'selected' : '' }}>
                                {{ $pr->tahun_periode }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>
  
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="Menunggu" {{ $pengajuan->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Disetujui" {{ $pengajuan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ $pengajuan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control">{{ $pengajuan->catatan }}</textarea>
                    <small id="error-catatan" class="error-text form-text text-danger"></small>
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
    $('#id_peserta').select2({
        width: '100%', // Full width
        allowClear: true
    });
    $("#form-edit-pengajuan-pelatihan").validate({
        rules: {
            id_pelatihan: { required: true },
            tanggal_pengajuan: { required: true },
            status: { required: true },
            id_peserta: { required: true },
            id_periode: { required: true },
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
                        tablePengajuanPelatihan.ajax.reload(); // Reload the datatable
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
