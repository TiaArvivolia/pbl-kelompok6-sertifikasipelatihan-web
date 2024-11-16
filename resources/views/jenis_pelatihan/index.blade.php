@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{url('/jenis_pelatihan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/jenis_pelatihan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <button onclick="modalAction('{{ url('jenis_pelatihan/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Ajax
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_jenis_pelatihan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jenis Pelatihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function modalAction(url = ''){
    $('#myModal').load(url, function(){
        $('#myModal').modal('show');
    });
}

var dataJenisPelatihan;
$(document).ready(function() {
    dataJenisPelatihan = $('#table_jenis_pelatihan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('jenis_pelatihan/list') }}",
            "dataType": "json",
            "type": "POST"
        },
        columns: [
            {
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: "nama_jenis_pelatihan",
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
});
</script>
@endpush
