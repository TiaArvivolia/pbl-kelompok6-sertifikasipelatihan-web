@empty($pengguna)
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
                    Data pengguna yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/pengguna') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pengguna :</th>
                        <td class="col-9">{{ $pengguna->id_pengguna }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username :</th>
                        <td class="col-9">{{ $pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap :</th>
                        <td class="col-9">{{ $pengguna->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $pengguna->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tempat Lahir :</th>
                        <td class="col-9">{{ $pengguna->tempat_lahir }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Lahir :</th>
                        <td class="col-9">{{ $pengguna->tanggal_lahir }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Kelamin :</th>
                        <td class="col-9">{{ $pengguna->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon :</th>
                        <td class="col-9">{{ $pengguna->no_telepon }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIK :</th>
                        <td class="col-9">{{ $pengguna->nik }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIDN :</th>
                        <td class="col-9">{{ $pengguna->nidn }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Agama :</th>
                        <td class="col-9">{{ $pengguna->agama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Alamat :</th>
                        <td class="col-9">{{ $pengguna->alamat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $pengguna->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Peran :</th>
                        <td class="col-9">{{ $pengguna->peran }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Foto Profil :</th>
                        <td class="col-9">
                            @if($pengguna->photo_profile)
                                <img src="{{ asset('storage/' . $pengguna->photo_profile) }}"  width="100">
                            @else
                                <span>Foto tidak tersedia</span>
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
