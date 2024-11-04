@empty($bidang_minat)
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
                    Data bidang minat yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/bidang-minat') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Bidang Minat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Bidang Minat :</th>
                        <td class="col-9">{{ $bidang_minat->id_bidang_minat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Bidang Minat :</th>
                        <td class="col-9">{{ $bidang_minat->kode_bidang_minat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Bidang Minat :</th>
                        <td class="col-9">{{ $bidang_minat->nama_bidang_minat }}</td>
                    </tr>
                    <!-- Tambahkan lebih banyak field di sini jika diperlukan -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
