@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{url('/bidang_minat/export_excel')}}" class="btn btn-sm btn-primary mt-1">
                <i class="fa fa-file-excel"></i> Export (Excel)
            </a>
            <a href="{{url('/bidang_minat/export_pdf')}}" class="btn btn-sm btn-warning mt-1">
                <i class="fa fa-file-pdf"></i> Export (PDF)
            </a>
            <button onclick="modalAction('{{ url('bidang_minat/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_bidang_minat"style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Bidang Minat</th>
                    <th>Nama Bidang Minat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" 
data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function modalAction(url = '') {
    $('#myModal').load(url, function(){
        $('#myModal').modal('show');
    });
}

var dataBidangMinat;
$(document).ready(function() {
    dataBidangMinat = $('#table_bidang_minat').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('bidang_minat/list') }}",
            dataType: "json",
            type: "POST",
        },
        columns: [
            {
                data: null,
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut
                }
            },
            { data: "kode_bidang_minat", orderable: true, searchable: true },
            { data: "nama_bidang_minat", orderable: true, searchable: true },
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
