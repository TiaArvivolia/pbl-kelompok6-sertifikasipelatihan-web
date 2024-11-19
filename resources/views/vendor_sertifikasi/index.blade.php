@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Vendor Sertifikasi</h3>
        <div class="card-tools">
            <a href="{{url('/vendor_sertifikasi/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export (Excel)</a>
            <a href="{{url('/vendor_sertifikasi/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export (PDF)</a>
            <button onclick="modalAction('{{ url('vendor_sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                <i class="fas fa-plus"></i> Tambah Vendor Sertifikasi
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
        <table class="table table-bordered table-striped table-hover table-sm" id="table_vendor_sertifikasi">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Vendor</th>
                    <th style="width: 15%;">No Telepon</th>
                    <th style="width: 15%;">Kota</th>
                    <th style="width: 30%;">Alamat</th>
                    <th class="text-center" style="width: 10%;">Aksi</th>
                </tr>
            </thead>
        </table>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function(){
            $('#myModal').modal('show');
        });
    }

    var dataVendorSertifikasi;
    $(document).ready(function() {
        dataVendorSertifikasi = $('#table_vendor_sertifikasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('vendor_sertifikasi/list') }}",
                "dataType": "json",
                "type": "POST"
            },
            columns: [
                {
                    data: null,
                    className: "text-center",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Generate row number
                    }
                },
                { data: "nama", orderable: true, searchable: true },
                { data: "no_telepon", orderable: true, searchable: true },
                { data: "kota", orderable: true, searchable: true },
                { data: "alamat", orderable: true, searchable: true },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
