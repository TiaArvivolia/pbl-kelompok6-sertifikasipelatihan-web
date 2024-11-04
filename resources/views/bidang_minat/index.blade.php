@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Bidang Minat</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('bidang_minat/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Bidang Minat</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_bidang_minat">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 20%;">Kode Bidang Minat</th>
                        <th style="width: 30%;">Nama Bidang Minat</th>
                        <th class="text-center" style="width: 25%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bidang_minat as $index => $bm)  <!-- Use $index to generate the number -->
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>  <!-- Display sequential number -->
                            <td>{{ $bm->kode_bidang_minat }}</td>
                            <td>{{ $bm->nama_bidang_minat }}</td>
                            <td class="text-center">
                                <!-- Show button with icon -->
                                <button onclick="modalAction('{{ url('/bidang_minat/' . $bm->id_bidang_minat . '/show_ajax') }}')" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </button>
                                <!-- Edit button with icon -->
                                <button onclick="modalAction('{{ url('/bidang_minat/' . $bm->id_bidang_minat . '/edit_ajax') }}')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete button with icon -->
                                <button onclick="modalAction('{{ url('/bidang_minat/' . $bm->id_bidang_minat . '/delete_ajax') }}')" class="btn btn-sm btn-danger">
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
        $('#table_bidang_minat').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ url('bidang_minat/list') }}",
                dataType: "json",
                type: "POST",
            },
            columns: [
                { data: null, className: "text-center", orderable: false, render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;  // Calculate the number to display
                }},
                { data: "kode_bidang_minat", orderable: true },
                { data: "nama_bidang_minat", orderable: true },
                { data: "aksi", className: "text-center", orderable: false }
            ]
        });
    });
</script>
@endpush
