@empty($sertifikasi)
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
                <a href="{{ url('/riwayat_sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Riwayat Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Riwayat Sertifikasi :</th>
                        <td class="col-9">{{ $sertifikasi->id_riwayat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pengguna :</th>
                        <td class="col-9">
                            {{ $sertifikasi->pengguna->dosen ? $sertifikasi->pengguna->dosen->nama_lengkap : ($sertifikasi->pengguna->tendik ? $sertifikasi->pengguna->tendik->nama_lengkap : 'Tidak Tersedia') }}
                        </td>
                    </tr>                    
                    <tr>
                        <th class="text-right col-3">Jenis Pengguna :</th>
                        <td class="col-9">{{ $sertifikasi->pengguna->jenisPengguna->nama_jenis_pengguna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Sertifikasi :</th>
                        <td class="col-9">{{ $sertifikasi->nama_sertifikasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level Sertifikasi :</th>
                        <td class="col-9">{{ $sertifikasi->level_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Sertifikasi :</th>
                        <td class="col-9">{{ $sertifikasi->jenis_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Sertifikat :</th>
                        <td class="col-9">{{ $sertifikasi->no_sertifikat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Terbit :</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($sertifikasi->tanggal_terbit)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Masa Berlaku :</th>
                        <td class="col-9">{{ $sertifikasi->masa_berlaku ? \Carbon\Carbon::parse($sertifikasi->masa_berlaku)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tahun Periode :</th>
                        <td class="col-9">{{ $sertifikasi->tahun_periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Penyelenggara :</th>
                        <td class="col-9">
                            @if(is_object($sertifikasi->penyelenggara))
                                {{ $sertifikasi->penyelenggara->nama }}
                            @else
                                Tidak tersedia
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Dokumen Sertifikat :</th>
                        <td class="col-9">
                            @if($sertifikasi->dokumen_sertifikat)
                                <a href="{{ asset('storage/' . $sertifikasi->dokumen_sertifikat) }}" target="_blank">Lihat Dokumen</a>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah:</th>
                        <td class="col-9">
                            @if($sertifikasi->mk_list && count($mataKuliahNames) > 0)
                                {{ implode(', ', $mataKuliahNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Bidang Minat:</th>
                        <td class="col-9">
                            @if($sertifikasi->bidang_minat_list && count($bidangMinatNames) > 0)
                                {{ implode(', ', $bidangMinatNames) }}
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
