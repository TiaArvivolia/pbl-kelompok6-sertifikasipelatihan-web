@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Pelatihan</h3>
        <div class="card-tools">
            <a href="{{url('/riwayat_pelatihan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/riwayat_pelatihan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <!-- Add Data Button with Icon -->
            <button onclick="modalAction('{{ url('/riwayat_pelatihan/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Riwayat Pelatihan
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
            <table class="table table-bordered table-sm table-striped table-hover" id="table-riwayat-pelatihan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th> <!-- Kolom Nama Pengguna -->
                        <th>Nama Pelatihan</th>
                        <th>Penyelenggara</th>
                        <th>Level Pelatihan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Lokasi</th>
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

var tableRiwayatPelatihan;
$(document).ready(function(){
    tableRiwayatPelatihan = $('#table-riwayat-pelatihan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('riwayat_pelatihan/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.filter_level_pelatihan = $('.filter_level_pelatihan').val();
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
                data: "nama_lengkap",  // Menampilkan nama pengguna
                orderable: false,
                searchable: true
            },
            {
                data: "nama_pelatihan",
                orderable: true,
                searchable: true
            },
            {
                data: "penyelenggara",
                orderable: true,
                searchable: true
            },
            {
                data: "level_pelatihan",
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
                data: "lokasi",
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

    $('.filter_level_pelatihan').change(function() {
        tableRiwayatPelatihan.draw();
    });
});
</script>
@endpush
