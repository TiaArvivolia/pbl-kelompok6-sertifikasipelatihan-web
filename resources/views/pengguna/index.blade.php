@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Bidang Minat</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/pengguna/import') }}')" class="btn btn-sm btn-info mt-1">Import pengguna</button>
            <a href="{{url('/pengguna/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export pengguna(Excel)</a>
            <a href="{{url('/pengguna/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export pengguna (PDF)</a>
            <button onclick="modalAction('{{ url('pengguna/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah pengguna</button>
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
    </div>
    <table class="table table-bordered table-striped table-hover table-sm" id="table_pengguna">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Peran</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static" data-keyboard="false" 
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
            dataBidangMinat = $('#table_pengguna').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('pengguna/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    {
                        data: "id_pengguna",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, 
                    {
                        data: "username",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "nama_lengkap",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "peran",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
