@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Pengguna</h3>
        <div class="card-tools">
            {{-- <button onclick="modalAction('{{ url('/pengguna/import') }}')" class="btn btn-sm btn-info mt-1">Import Pengguna</button>
            <a href="{{ url('/pengguna/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Pengguna (Excel)</a>
            <a href="{{ url('/pengguna/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Pengguna (PDF)</a> --}}
            <button onclick="modalAction('{{ url('pengguna/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Pengguna</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            </script>
        @endif

        <div class="form-group row">
            <label class="col-1 control-label col-form-label">Filter:</label>
            <div class="col-3">
                <select class="form-control" id="peran_filter" name="peran_filter">
                    <option value="">- Semua -</option>
                    <option value="Admin">Admin</option>
                    <option value="Manager">Manager</option>
                    <option value="Staff">Staff</option>
                    <!-- Add more roles as needed -->
                </select>
                <small class="form-text text-muted">Peran Pengguna</small>
            </div>
        </div>
        

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pengguna">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">ID</th>
                        <th style="width: 20%;">Username</th>
                        <th style="width: 30%;">Nama Lengkap</th>
                        <th style="width: 20%;">Peran</th>
                        <th class="text-center" style="width: 25%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengguna as $user)
                        <tr>
                            <td class="text-center">{{ $user->id_pengguna }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->nama_lengkap }}</td>
                            <td>{{ $user->peran }}</td>
                            <td class="text-center">
                                <!-- Show button with icon -->
                                <button onclick="modalAction('{{ url('/pengguna/' . $user->id_pengguna . '/show_ajax') }}')" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show <!-- Nama untuk "Show" -->
                                </button>
                                <!-- Edit button with icon -->
                                <button onclick="modalAction('{{ url('/pengguna/' . $user->id_pengguna . '/edit_ajax') }}')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit <!-- Nama untuk "Edit" -->
                                </button>
                                <!-- Delete button with icon -->
                                <button onclick="modalAction('{{ url('/pengguna/' . $user->id_pengguna . '/delete_ajax') }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus <!-- Nama untuk "Hapus" -->
                                </button>
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
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

    var dataPengguna;
    $(document).ready(function() {
    dataPengguna = $('#table_pengguna').DataTable({
        responsive: true,
        autoWidth: false,
        serverSide: true,
        ajax: {
            url: "{{ url('pengguna/list') }}",
            dataType: "json",
            type: "POST",
            data: function(d) {
                d.peran_filter = $('#peran_filter').val(); // Get the selected role
            }
        },
        columns: [
            { data: "id_pengguna", className: "text-center", orderable: false },
            { data: "username", orderable: true },
            { data: "nama_lengkap", orderable: true },
            { data: "peran", orderable: true },
            { data: "aksi", className: "text-center", orderable: false }
        ]
    });

    // Filter change event
    $('#peran_filter').change(function() {
        dataPengguna.ajax.reload(); // Reload the table data
    });
});

</script>
@endpush
