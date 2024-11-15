@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Sertifikasi</h3>
        <div class="card-tools">
            <!-- Add Data Button with Icon -->
            <button onclick="modalAction('{{ url('/riwayat_sertifikasi/create_ajax') }}')" class="btn btn-success btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Riwayat Sertifikasi
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
            <table class="table table-bordered table-sm table-striped table-hover" id="table-riwayat-sertifikasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Level Sertifikasi</th>
                        <th>Nama Sertifikasi</th>
                        <th>No Sertifikat</th>
                        <th>Tanggal Terbit</th>
                        <th>Masa Berlaku</th>
                        {{-- <th>Penyelenggara</th> --}}
                        <th>Dokumen Sertifikat</th>
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

var tableRiwayatSertifikasi;
$(document).ready(function(){
    tableRiwayatSertifikasi = $('#table-riwayat-sertifikasi').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": "{{ url('riwayat_sertifikasi/list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.filter_level_sertifikasi = $('.filter_level_sertifikasi').val();
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
                data: "level_sertifikasi", // Menampilkan level sertifikasi
                orderable: true,
                searchable: true
            },
            {
                data: "nama_sertifikasi", // Menampilkan nama sertifikasi
                orderable: true,
                searchable: true
            },
            {
                data: "no_sertifikat", // Menampilkan no sertifikat
                orderable: true,
                searchable: true
            },
            {
                data: "tanggal_terbit", // Menampilkan tanggal terbit
                orderable: true,
                searchable: true
            },
            {
                data: "masa_berlaku", // Menampilkan masa berlaku
                orderable: true,
                searchable: true
            },
            // {
            //     data: "penyelenggara.nama", // Menampilkan penyelenggara
            //     orderable: true,
            //     searchable: true
            // },
            {
                data: "dokumen_sertifikat", // Menampilkan dokumen sertifikat
                orderable: false,
                searchable: false,
                render: function(data) {
                    return data ? `<a href="${data}" target="_blank">Lihat Dokumen</a>` : 'No Document';
                }
            },
            {
                data: "aksi", 
                className: "text-center",
                orderable: false,
                searchable: false
            }
        ]
    });

    $('.filter_level_sertifikasi').change(function() {
        tableRiwayatSertifikasi.draw();
    });
});
</script>
@endpush
