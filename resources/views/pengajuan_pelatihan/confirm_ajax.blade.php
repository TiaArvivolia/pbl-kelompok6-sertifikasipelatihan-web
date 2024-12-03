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
<form action="{{ url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/delete_ajax') }}" method="POST" id="form-delete-pengajuan-pelatihan">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Pengajuan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda ingin menghapus pengajuan berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pengajuan :</th>
                        <td class="col-9">{{ $pengajuan->id_pengajuan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pelatihan :</th>
                        <td class="col-9">{{ $pengajuan->daftarPelatihan  ? $pengajuan->daftarPelatihan ->nama_pelatihan : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Pengajuan :</th>
                        <td class="col-9">{{ $pengajuan->tanggal_pengajuan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">
                            @php
                                $statusClass = '';
                                $statusText = $pengajuan->status;
                    
                                switch ($pengajuan->status) {
                                    case 'Menunggu':
                                        $statusClass = 'bg-warning'; // Yellow
                                        break;
                                    case 'Disetujui':
                                        $statusClass = 'bg-success'; // Green
                                        break;
                                    case 'Ditolak':
                                        $statusClass = 'bg-danger'; // Red
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary'; // Default (Gray) if status is unknown
                                        $statusText = 'Tidak Diketahui';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                    </tr>  
                    {{-- <tr>
                        <th class="text-right col-3">Peserta :</th>
                        <td class="col-9">{{ $pengajuan->id_peserta }}</td>
                    </tr> --}}
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-delete-pengajuan-pelatihan").validate({
        rules: {},
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
                        tablePengajuanPelatihan.ajax.reload(); // Pastikan ini sesuai dengan nama datatable Anda
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
