@empty($mata_kuliah)
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
                    Data mata kuliah yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/mata-kuliah') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Mata Kuliah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Mata Kuliah :</th>
                        <td class="col-9">{{ $mata_kuliah->id_mata_kuliah }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Mata Kuliah :</th>
                        <td class="col-9">{{ $mata_kuliah->kode_mk }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Mata Kuliah :</th>
                        <td class="col-9">{{ $mata_kuliah->nama_mk }}</td>
                    </tr>
                    <!-- Add more fields here if needed -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
