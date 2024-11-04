@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Vendor Sertifikai</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/vendorsertifikasi/import') }}')" class="btn btn-sm btn-info mt-1">Import Vendor Sertifikai</button>
            <a href="{{url('/vendorsertifikasi/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Vendor Sertifikai (Excel)</a>
            <a href="{{url('/vendorsertifikasi/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Vendor Sertifikai (PDF)</a>
            <button onclick="modalAction('{{ url('vendorsertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Vendor Sertifikai</button>
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
    <table class="table table-bordered table-striped table-hover table-sm" id="table_vendorsertifikasi">
        <thead>
            <tr>
                <th>ID Vendor Sertifikai</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>No. Telp</th>
                <th>Website</th>
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

        var dataMataKuliah;
        $(document).ready(function() {
            dataMataKuliah = $('#table_vendorsertifikasi').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('vendorsertifikasi/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    {
                        data: "id_vendorsertifikasi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, 
                    {
                        data: "nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "alamat",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "kota",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "no_telepon",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "website",
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
