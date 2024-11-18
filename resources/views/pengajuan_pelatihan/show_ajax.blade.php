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
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengajuan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pengajuan :</th>
                        <td class="col-9">{{ $pengajuan->id_pengajuan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">ID Pelatihan :</th>
                        <td class="col-9">{{ $pengajuan->id_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan :</th>
                        <td class="col-9">{{ $pengajuan->daftarPelatihan  ? $pengajuan->daftarPelatihan ->nama_pelatihan : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Pengajuan :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">{{ $pengajuan->status }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Catatan :</th>
                        <td class="col-9">{{ $pengajuan->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3"> Peserta :</th>
                        <td class="col-9">
                            @if($pengajuan->id_peserta && count($participantNames) > 0)
                                {{ implode(', ', $participantNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    
                    
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
