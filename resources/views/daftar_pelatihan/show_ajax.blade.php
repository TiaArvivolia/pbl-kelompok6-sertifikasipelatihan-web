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
                <a href="{{ url('/daftar-pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->id_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
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
                        <th class="text-right col-3">Kuota :</th>
                        <td class="col-9">{{ $pelatihan->kuota ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Lokasi :</th>
                        <td class="col-9">{{ $pelatihan->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Biaya :</th>
                        <td class="col-9">{{ $pelatihan->biaya ? 'Rp ' . number_format($pelatihan->biaya, 2, ',', '.') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah Jam :</th>
                        <td class="col-9">{{ $pelatihan->jml_jam ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor Pelatihan :</th>
                        <td class="col-9">{{ $pelatihan->vendorPelatihan ? $pelatihan->vendorPelatihan->nama : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah :</th>
                        <td class="col-9">{{ $pelatihan->mataKuliah ? $pelatihan->mataKuliah->nama_mk : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang Minat :</th>
                        <td class="col-9">{{ $pelatihan->bidangMinat ? $pelatihan->bidangMinat->nama_bidang_minat : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
