@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pengajuan Pelatihan</h3>
        <div class="card-tools">
            <a href="{{url('/pengajuan_pelatihan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/pengajuan_pelatihan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <!-- Add Data Button with Icon -->
            <button onclick="modalAction('{{ url('/pengajuan_pelatihan/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Pengajuan Pelatihan
            </button>
        </div>
    </div>
    

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped table-hover" id="table-pengajuan-pelatihan" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nama Pelatihan</th>
                        <th>Peserta Pelatihan</th> <!-- Kolom Nama Pengguna -->
                        <th>Status</th>
                        <th>Draft Surat Tugas</th>
                        {{-- <th>Catatan</th> --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function modalAction(url = ''){
    $('#myModal').load(url, function(){
        $('#myModal').modal('show');
    });
}

var tablePengajuanPelatihan;
$(document).ready(function(){
    tablePengajuanPelatihan = $('#table-pengajuan-pelatihan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('pengajuan_pelatihan/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.filter_status = $('.filter_status').val();
            }
        },
        columns: [
            {
                data: "DT_RowIndex", 
                className: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: "tanggal_pengajuan",
                orderable: true,
                searchable: true
            },
            {
                data: "nama_pelatihan",
                orderable: true,
                searchable: true
            },
            {
            data: 'nama_lengkap',  // Nama lengkap peserta yang ditampilkan
            name: 'nama_lengkap',
            orderable: false,  // Tidak bisa diurutkan
            searchable: true,  // Bisa dicari
            },
            {
                data: "status",
                orderable: true,
                searchable: true,
                render: function(data, type, row) {
                    // Check the status value and apply a corresponding class
                    var statusClass = '';
                    var statusText = '';

                    if (data === 'Menunggu') {
                        statusClass = 'bg-warning'; // Yellow
                        statusText = 'Menunggu';
                    } else if (data === 'Disetujui') {
                        statusClass = 'bg-success'; // Green
                        statusText = 'Disetujui';
                    } else if (data === 'Ditolak') {
                        statusClass = 'bg-danger'; // Red
                        statusText = 'Ditolak';
                    }

                    // Return the HTML with the appropriate class and status text
                    return '<span class="badge ' + statusClass + '">' + statusText + '</span>';
                }
            },
             {
                 data: "draft",
                 orderable: true,
                 searchable: true
             },
            {
                data: "aksi",
                className: "text-center",
                orderable: false,
                searchable: false
            }
        ]
    });

    $('.filter_status').change(function() {
        tablePengajuanPelatihan.draw();
    });
});
</script>
@endpush