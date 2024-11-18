@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pelatihan</h3>
        <div class="card-tools">
            <a href="{{url('/daftar_pelatihan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/daftar_pelatihan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <!-- Add Data Button with Icon -->
            <button onclick="modalAction('{{ url('/daftar_pelatihan/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Pelatihan
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
            <table class="table table-bordered table-sm table-striped table-hover" id="table-pelatihan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Level Pelatihan</th>
                        <th>Nama Pelatihan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Kuota</th>
                        <th>Lokasi</th>
                        <th>Vendor Pelatihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

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

var tablePelatihan;
$(document).ready(function(){
    tablePelatihan = $('#table-pelatihan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('daftar_pelatihan/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                // You can add any custom filters here, similar to the filter_pengguna in dosen
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
                data: "level_pelatihan",
                orderable: true,
                searchable: true
            },
            {
                data: "nama_pelatihan",
                orderable: true,
                searchable: true
            },
            {
                data: "tanggal_mulai",
                orderable: true,
                searchable: true
            },
            {
                data: "tanggal_selesai",
                orderable: true,
                searchable: true
            },
            {
                data: "kuota",
                orderable: true,
                searchable: true
            },
            {
                data: "lokasi",
                orderable: true,
                searchable: true
            },
            {
                data: "vendor_pelatihan.nama", // Assuming the vendor has a name attribute
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

    // Optionally, you can add event listeners for custom filters or actions
});
</script>
@endpush
