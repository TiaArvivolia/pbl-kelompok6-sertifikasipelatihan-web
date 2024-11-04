@empty($vendor)
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
                    Data vendor sertifikasi yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/vendor-sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Vendor Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Vendor Sertifikasi :</th>
                        <td class="col-9">{{ $vendor->id_vendor_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama :</th>
                        <td class="col-9">{{ $vendor->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Alamat :</th>
                        <td class="col-9">{{ $vendor->alamat }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kota :</th>
                        <td class="col-9">{{ $vendor->kota }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon :</th>
                        <td class="col-9">{{ $vendor->no_telepon }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Website :</th>
                        <td class="col-9">{{ $vendor->website }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
