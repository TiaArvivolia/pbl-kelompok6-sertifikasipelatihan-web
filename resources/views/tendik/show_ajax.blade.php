@empty($tendik)
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
                <a href="{{ url('/tendik') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Tendik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Tendik :</th>
                        <td class="col-9">{{ $tendik->id_tendik }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">ID Pengguna :</th>
                        <td class="col-9">{{ $tendik->pengguna->id_pengguna }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap :</th>
                        <td class="col-9">{{ $tendik->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $tendik->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No. Telepon :</th>
                        <td class="col-9">{{ $tendik->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $tendik->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang Minat:</th>
                        <td class="col-9">
                            @if($tendik->bidang_minat_list && count($bidangMinatNames) > 0)
                                {{ implode(', ', $bidangMinatNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>                   
                    <tr>
                        <th class="text-right col-3">Gambar Profil :</th>
                        <td class="col-9">
                            @if($tendik->gambar_profil)
                                <img src="{{ asset('storage/' . $tendik->gambar_profil) }}" alt="Gambar Profil" class="img-fluid" width="150">
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
