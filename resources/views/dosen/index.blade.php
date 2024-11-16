@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Dosen</h3>
        <div class="card-tools">
            <a href="{{url('/dosen/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/dosen/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <!-- Add Data Button with Icon -->
            <button onclick="modalAction('{{ url('/dosen/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Dosen
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
        <table class="table table-bordered table-sm table-striped table-hover" id="table-dosen">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIP</th>
                    <th>NIDN</th>
                    <th>No Telepon</th>
                    <th>Email</th>
                    {{-- <th>ID Pengguna</th> <!-- Added ID Pengguna Column --> --}}
                    <th>Username</th> <!-- Added Username Column -->
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

var tableDosen;
$(document).ready(function(){
    tableDosen = $('#table-dosen').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('dosen/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.filter_pengguna = $('.filter_pengguna').val();
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
                data: "nama_lengkap",
                orderable: true,
                searchable: true
            },
            {
                data: "nip",
                orderable: true,
                searchable: true
            },
            {
                data: "nidn",
                orderable: true,
                searchable: true
            },
            {
                data: "no_telepon",
                orderable: true,
                searchable: true
            },
            {
                data: "email",
                orderable: true,
                searchable: true
            },
            // {
            //     data: "pengguna.id_pengguna", // Displaying ID Pengguna
            //     orderable: false,
            //     searchable: false
            // },
            {
                data: "pengguna.username", // Displaying Username
                orderable: false,
                searchable: false
            },
            {
                data: "aksi",
                className: "text-center",
                orderable: false,
                searchable: false
            }
        ]
    });

    $('.filter_pengguna').change(function() {
        tableDosen.draw();
    });
});
</script>
@endpush