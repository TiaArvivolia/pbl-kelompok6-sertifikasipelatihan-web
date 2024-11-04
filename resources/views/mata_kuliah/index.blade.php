@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Mata Kuliah</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/mata_kuliah/import') }}')" class="btn btn-sm btn-info mt-1">Import Mata Kuliah</button>
            <a href="{{url('/mata_kuliah/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Mata Kuliah (Excel)</a>
            <a href="{{url('/mata_kuliah/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Mata Kuliah (PDF)</a>
            <button onclick="modalAction('{{ url('mata_kuliah/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Mata Kuliah</button>
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
    <table class="table table-bordered table-striped table-hover table-sm" id="table_mata_kuliah">
        <thead>
            <tr>
                <th>ID Mata Kuliah</th>
                <th>Kode MK</th>
                <th>Nama MK</th>
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
            dataMataKuliah = $('#table_mata_kuliah').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('mata_kuliah/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    {
                        data: "id_mata_kuliah",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, 
                    {
                        data: "kode_mk",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, 
                    {
                        data: "nama_mk",
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
