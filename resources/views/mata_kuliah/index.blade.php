@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Mata Kuliah</h3>
        <div class="card-tools">
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

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mata_kuliah">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 20%;">Kode Mata Kuliah</th>
                        <th style="width: 30%;">Nama Mata Kuliah</th>
                        <th class="text-center" style="width: 25%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mata_kuliah as $index => $mk)  <!-- Use $index to generate the number -->
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>  <!-- Display sequential number -->
                            <td>{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td class="text-center">
                                <!-- Show button with icon -->
                                <button onclick="modalAction('{{ url('/mata_kuliah/' . $mk->id_mata_kuliah  . '/show_ajax') }}')" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </button>
                                <!-- Edit button with icon -->
                                <button onclick="modalAction('{{ url('/mata_kuliah/' . $mk->id_mata_kuliah . '/edit_ajax') }}')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete button with icon -->
                                <button onclick="modalAction('{{ url('/mata_kuliah/' . $mk->id_mata_kuliah . '/delete_ajax') }}')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
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

    $(document).ready(function() {
        $('#table_mata_kuliah').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ url('mata_kuliah/list') }}",
                dataType: "json",
                type: "POST",
            },
            columns: [
                { data: null, className: "text-center", orderable: false, render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;  // Calculate the number to display
                }},
                { data: "kode_mk", orderable: true },
                { data: "nama_mk", orderable: true },
                { data: "aksi", className: "text-center", orderable: false }
            ]
        });
    });
</script>
@endpush
