@empty($pimpinan)
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
                <a href="{{ url('/pimpinan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pimpinan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pimpinan :</th>
                        <td class="col-9">{{ $pimpinan->id_pimpinan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">ID Pengguna :</th>
                        <td class="col-9">{{ $pimpinan->pengguna->id_pengguna }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username Pengguna :</th>
                        <td class="col-9">{{ $pimpinan->pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap :</th>
                        <td class="col-9">{{ $pimpinan->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $pimpinan->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIDN :</th>
                        <td class="col-9">{{ $pimpinan->nidn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No. Telepon :</th>
                        <td class="col-9">{{ $pimpinan->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $pimpinan->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Gambar Profil :</th>
                        <td class="col-9">
                            <img src="{{ asset('storage/' . $pimpinan->gambar_profil) }}" alt="Gambar Profil" class="img-fluid" width="150">
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
