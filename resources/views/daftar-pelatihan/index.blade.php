@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pelatihan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/daftar-pelatihan/import') }}')" class="btn btn-sm btn-info mt-1">Import Daftar pelatihan</button>
            <a href="{{url('/daftar-pelatihan/export_excel')}}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Daftar pelatihan (Excel)</a>
            <a href="{{url('/daftar-pelatihan/export_pdf')}}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Daftar pelatihan (PDF)</a>
            <button onclick="modalAction('{{ url('daftar-pelatihan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Daftar pelatihan</button>
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

        <!-- Container for the list view -->
        <div id="vendor_list" class="list-group">
            <!-- Content will be populated by DataTable -->
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
            $('#vendor_list').html(''); // Clear the list

            var dataMataKuliah = $('#table_daftar-pelatihan').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('daftar-pelatihan/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    { data: "foto", orderable: false, searchable: false },
                    { data: "nama", orderable: true, searchable: true },
                    { data: "alamat", orderable: true, searchable: true },
                    { data: "kota", orderable: true, searchable: true },
                    { data: "no_telepon", orderable: true, searchable: true },
                    { data: "website", orderable: false, searchable: false }
                ],
                rowCallback: function(row, data) {
                    var listItem = `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h5>${data.nama}</h5>
                                <p>${data.alamat}</p>
                                <p>${data.kota}</p>
                                <p>${data.no_telepon}</p>
                            </div>
                            <div class="text-right">
                                <img src="${data.foto}" alt="${data.nama}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <a href="${data.website}" target="_blank" class="btn btn-primary mt-2">Selengkapnya</a>
                            </div>
                        </div>
                    `;
                    $('#vendor_list').append(listItem);
                },
                initComplete: function() {
                    // Remove DataTable's table display
                    $('#table_daftar-pelatihan').hide();
                }
            });
        });
    </script>
@endpush
