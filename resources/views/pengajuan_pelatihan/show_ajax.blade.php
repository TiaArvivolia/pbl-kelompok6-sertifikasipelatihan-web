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
                        <th class="text-right col-3">Periode :</th>
                        <td class="col-9">
                            @if($pengajuan->periode && is_object($pengajuan->periode))  <!-- Mengakses relasi periode -->
                                {{ $pengajuan->periode->tahun_periode }}  <!-- Menampilkan tahun_periode dari relasi periode -->
                            @else
                                Data periode tidak ditemukan
                            @endif
                        </td>
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
                    <tr>
                        <th class="text-right col-3">Catatan :</th>
                        <td class="col-9">{{ $pengajuan->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3"> Peserta :</th>
                        <td class="col-9">
                            @if(!empty($participantNames))
                                {{-- Use implode to join names with <br> for line breaks --}}
                                {!! implode('<br>', $participantNames) !!}
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
