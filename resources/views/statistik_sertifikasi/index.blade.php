@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Statistik Sertifikasi Dosen dan Tendik</h3>
        <!-- Filter form with select elements -->
        <form id="filter-form" method="GET" class="d-flex ml-auto">
            <div class="me-5"> <!-- Increased margin to add more space -->
    <select class="form-control" id="nama_lengkap" name="nama_lengkap">
        <option value="">Pilih Nama Pengguna</option>
        @foreach($dosenList as $dosen)
            <option value="{{ $dosen->nama_lengkap }}">{{ $dosen->nama_lengkap }}</option>
        @endforeach
        @foreach($tendikList as $tendik)
            <option value="{{ $tendik->nama_lengkap }}">{{ $tendik->nama_lengkap }}</option>
        @endforeach
    </select>
</div>

<div class="me-5"> <!-- Increased margin to add more space -->
    <select class="form-control" id="jenis_pengguna" name="jenis_pengguna">
        <option value="">Pilih Jenis Pengguna</option>
        @foreach($jenisPenggunaList as $jenisPengguna)
            @if($jenisPengguna->id_jenis_pengguna == 2 || $jenisPengguna->id_jenis_pengguna == 3)
                <option value="{{ $jenisPengguna->nama_jenis_pengguna }}">{{ $jenisPengguna->nama_jenis_pengguna }}</option>
            @endif
        @endforeach
    </select>
</div>

        </form>
        
    </div>
    
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover table-sm" id="table-statistik-sertifikasi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Total Sertifikasi</th>
                    <th>Jenis Pengguna</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>
var datatable;
$(document).ready(function() {
    // Initialize DataTable
    datatable = $('#table-statistik-sertifikasi').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('statistik_sertifikasi/list') }}",
            type: 'POST',
            data: function(d) {
                d.nama_lengkap = $('#nama_lengkap').val();  // Add the nama_lengkap filter
                d.jenis_pengguna = $('#jenis_pengguna').val();  // Add the jenis_pengguna filter
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);  // Debugging error log
                alert('An error occurred while fetching data!');
            }
        },
        columns: [
            { data: null, orderable: false, searchable: false },
            { data: 'nama_lengkap' },
            { data: 'total_sertifikasi' },
            { data: 'jenis_pengguna' }
        ],
        rowCallback: function(row, data, index) {
            $('td:eq(0)', row).html(index + 1);  // Adding row number in the first column
        }
    });

    // Apply filter when either filter is changed
    $('#nama_lengkap, #jenis_pengguna').on('change', function() {
        datatable.draw();  // Redraw table with filters
    });
});

</script>
@endpush
