@empty($pelatihan)
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
                <a href="{{ url('/riwayat_pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Riwayat Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Riwayat Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->id_riwayat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pengguna :</th>
                        <td class="col-9">
                            {{ $pelatihan->pengguna->dosen ? $pelatihan->pengguna->dosen->nama_lengkap : ($pelatihan->pengguna->tendik ? $pelatihan->pengguna->tendik->nama_lengkap : 'Tidak Tersedia') }}
                        </td>
                    </tr>                    
                    <tr>
                        <th class="text-right col-3">Jenis Pengguna :</th>
                        <td class="col-9">{{ $pelatihan->pengguna->jenisPengguna->nama_jenis_pengguna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="text-right col-3">Penyelenggara :</th>
                        <td class="col-9">
                            @if($pelatihan->penyelenggara)
                                {{ $pelatihan->penyelenggara->nama }}
                            @else
                                Data penyelenggara tidak ditemukan
                            @endif
                        </td>
                    </tr> --}}
                    <tr>
                        <th class="text-right col-3">Level Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->level_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai :</th>
                        <td class="col-9">{{ $pelatihan->tanggal_mulai ? \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai :</th>
                        <td class="col-9">{{ $pelatihan->tanggal_selesai ? \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Periode :</th>
                        <td class="col-9">
                            @if($pelatihan->periode && is_object($pelatihan->periode))  <!-- Mengakses relasi periode -->
                                {{ $pelatihan->periode->tahun_periode }}  <!-- Menampilkan tahun_periode dari relasi periode -->
                            @else
                                Data periode tidak ditemukan
                            @endif
                        </td>
                    </tr>                    
                    <tr>
                        <th class="text-right col-3">Lokasi :</th>
                        <td class="col-9">{{ $pelatihan->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Dokumen Pelatihan :</th>
                        <td class="col-9">
                            @if($pelatihan->dokumen_pelatihan)
                                <a href="{{ asset('storage/' . $pelatihan->dokumen_pelatihan) }}" target="_blank">Lihat Dokumen</a>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah:</th>
                        <td class="col-9">
                            @if($pelatihan->mk_list && count($mataKuliahNames) > 0)
                                {{ implode(', ', $mataKuliahNames) }}
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="text-right col-3">Bidang Minat:</th>
                        <td class="col-9">
                            @if($pelatihan->bidang_minat_list && count($bidangMinatNames) > 0)
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
