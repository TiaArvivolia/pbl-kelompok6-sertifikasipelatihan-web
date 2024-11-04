@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Draft Permohonan Surat Tugas</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/draft-surat-tugas/create') }}')" class="btn btn-sm btn-success mt-1">Tambah</button>
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

        <table class="table table-bordered table-striped table-hover table-sm" id="table_draft_surat_tugas">
            <thead>
                <tr>
                    <th>ID Draft</th>
                    <th>ID Pengajuan</th>
                    <th>ID Pengguna</th>
                    <th>Tanggal Ditugaskan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lokasi Pelatihan</th>
                    <th>Disetujui Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
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
            $('#table_draft_surat_tugas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('draft-surat-tugas/list') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                columns: [
                    { data: "id_draft", className: "text-center" },
                    { data: "id_pengajuan", className: "text-center" },
                    { data: "id_pengguna", className: "text-center" },
                    { data: "tanggal_ditugaskan", className: "text-center" },
                    { data: "status", className: "text-center" },
                    { data: "catatan" },
                    { data: "tanggal_mulai", className: "text-center" },
                    { data: "tanggal_selesai", className: "text-center" },
                    { data: "lokasi_pelatihan" },
                    { data: "disetujui_oleh" },
                    {
                        data: null,
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button onclick="editDraft(${row.id_draft})" class="btn btn-sm btn-primary">Edit</button>
                                <button onclick="deleteDraft(${row.id_draft})" class="btn btn-sm btn-danger">Hapus</button>
                                <button onclick="downloadDraft(${row.id_draft})" class="btn btn-sm btn-info">Download</button>
                            `;
                        }
                    }
                ]
            });
        });

        function editDraft(id) {
            modalAction(`{{ url('/draft-surat-tugas/edit') }}/${id}`);
        }

        function deleteDraft(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/draft-surat-tugas/delete') }}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Dihapus!',
                                'Data berhasil dihapus.',
                                'success'
                            );
                            $('#table_draft_surat_tugas').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data gagal dihapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function downloadDraft(id) {
            window.location.href = `{{ url('/draft-surat-tugas/download') }}/${id}`;
        }
    </script>
@endpush
