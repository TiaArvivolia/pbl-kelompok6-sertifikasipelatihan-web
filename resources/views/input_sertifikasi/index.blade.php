@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Kelola Riwayat Sertifikasi</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('input_sertifikasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Riwayat Sertifikasi</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_riwayat_sertifikasi">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
    
                        <th>Nama Sertifikasi</th>
                        <th>Jenis Sertifikasi</th>
                        <th>Tanggal Terbit</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayat_sertifikasi as $index => $cert)  <!-- Use $index to generate the number -->
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $cert->nama_sertifikasi }}</td>
                            <td>{{ $cert->no_sertifikat }}</td>
                            <td>{{ $cert->tanggal_terbit }}</td>
                            <td>
                                <a href="{{ asset('storage/dokumen/' . $cert->dokumen_sertifikat) }}" target="_blank">Lihat Dokumen</a>
                            </td>
                            <td>{{ $cert->diselenggarakan_oleh }}</td>
                            <td>{{ $cert->tag_mk }}</td>
                            <td>{{ $cert->tag_bidang_minat }}</td>
                            <td class="text-center">
                                <!-- Show button with icon -->
                                <button onclick="modalAction('{{ url('/input_sertifikasi/' . $cert->id_riwayat . '/show_ajax') }}')" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Show
                                </button>
                                <!-- Edit button with icon -->
                                <button onclick="modalAction('{{ url('/input_sertifikasi/' . $cert->id_riwayat . '/edit_ajax') }}')" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete button with icon -->
                                <button onclick="modalAction('{{ url('/input_sertifikasi/' . $cert->id_riwayat . '/delete_ajax') }}')" class="btn btn-sm btn-danger">
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
        $('#table_riawayat_sertifikasi').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ url('input_sertifikasi/list') }}",
                dataType: "json",
                type: "POST",
            },
            columns: [
                { data: null, className: "text-center", orderable: false, render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }},
                { data: "id_dosen", orderable: true },
                { data: "id_sertifikasi", orderable: true },
                { data: "nama_sertifikasi", orderable: true },
                { data: "no_sertifikat", orderable: true },
                { data: "jenis_sertifikasi", orderable: true },
                { data: "tanggal_terbit", orderable: true },
                { data: "masa_berlaku", orderable: true },
                { data: "penyelenggara", orderable: true },
                { data: "dokumen_sertifikat", orderable: false },
                { data: "diselenggarakan_oleh", orderable: true },
                { data: "tag_mk", orderable: true },
                { data: "tag_bidang_minat", orderable: true },
                { data: "aksi", className: "text-center", orderable: false }
            ]
        });
    });
</script>
@endpush


