@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Vendor Sertifikasi</h3>
        <div class="card-tools">
            <a href="{{url('/vendor_sertifikasi/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('vendor_sertifikasi/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <button onclick="modalAction('{{ url('vendor_sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Vendor Sertifikasi</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_vendor_sertifikasi">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Vendor</th>
                        <th style="width: 30%;">Alamat</th>
                        <th style="width: 15%;">Kota</th>
                        <th style="width: 15%;">No Telepon</th>
                        <th class="text-center" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $index => $vendor)  <!-- Use $index to generate the number -->
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>  <!-- Display sequential number -->
                            <td>{{ $vendor->nama }}</td>
                            <td>{{ $vendor->alamat }}</td>
                            <td>{{ $vendor->kota }}</td>
                            <td>{{ $vendor->no_telepon }}</td>
                            <td class="text-center">
                                <!-- Show button with icon -->
                                <button onclick="modalAction('{{ url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/show_ajax') }}')" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </button>
                                <!-- Edit button with icon -->
                                <button onclick="modalAction('{{ url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/edit_ajax') }}')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete button with icon -->
                                <button onclick="modalAction('{{ url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/delete_ajax') }}')" class="btn btn-sm btn-danger">
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
        $('#table_vendor_sertifikasi').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ url('vendor_sertifikasi/list') }}",
                dataType: "json",
                type: "POST",
            },
            columns: [
                { data: null, className: "text-center", orderable: false, render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;  // Calculate the number to display
                }},
                { data: "nama", orderable: true },
                { data: "alamat", orderable: true },
                { data: "kota", orderable: true },
                { data: "no_telepon", orderable: true },
                { data: "aksi", className: "text-center", orderable: false }
            ]
        });
    });
</script>
@endpush
