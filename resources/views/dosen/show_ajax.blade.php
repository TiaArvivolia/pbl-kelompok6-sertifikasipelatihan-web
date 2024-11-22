@empty($dosen)
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
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Dosen :</th>
                        <td class="col-9">{{ $dosen->id_dosen }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">ID Pengguna :</th>
                        <td class="col-9">{{ $dosen->pengguna->id_pengguna }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username Pengguna :</th>
                        <td class="col-9">{{ $dosen->pengguna->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap :</th>
                        <td class="col-9">{{ $dosen->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $dosen->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIDN :</th>
                        <td class="col-9">{{ $dosen->nidn }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tempat Lahir :</th>
                        <td class="col-9">{{ $dosen->tempat_lahir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Lahir :</th>
                        <td class="col-9">
                            {{ $dosen->tanggal_lahir ? \Carbon\Carbon::parse($dosen->tanggal_lahir)->format('d-m-Y') : '-' }}
                        </td>
                    </tr>                    
                    <tr>
                        <th class="text-right col-3">No. Telepon :</th>
                        <td class="col-9">{{ $dosen->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $dosen->email ?? '-' }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="text-right col-3">Mata Kuliah :</th>
                        <td class="col-9">{{ $dosen->mataKuliah ? $dosen->mataKuliah->nama_mk : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang Minat :</th>
                        <td class="col-9">{{ $dosen->bidangMinat ? $dosen->bidangMinat->nama_bidang_minat : '-' }}</td>
                    </tr>                     --}}
                    <tr>
                        <th class="text-right col-3">Mata Kuliah:</th>
                        <td class="col-9">
                            @if($dosen->mk_list && count($mataKuliahNames) > 0)
                                {{ implode(', ', $mataKuliahNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Bidang Minat:</th>
                        <td class="col-9">
                            @if($dosen->bidang_minat_list && count($bidangMinatNames) > 0)
                                {{ implode(', ', $bidangMinatNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Gambar Profil :</th>
                        <td class="col-9">
                            @if($dosen->gambar_profil)
                                <img src="{{ asset('storage/' . $dosen->gambar_profil) }}" alt="Gambar Profil" class="img-fluid" width="150">
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
