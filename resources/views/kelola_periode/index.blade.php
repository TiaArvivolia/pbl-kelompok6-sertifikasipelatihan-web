@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <!-- Filter Form -->
                <div class="form-inline">
                    <select class="form-control mr-2" id="tahun_periode" name="tahun_periode">
                        <option value="">- Semua -</option>
                        @foreach ($yearsList as $year)
                            <option value="{{ $year->tahun_periode }}" 
                                    {{ $selectedYear == $year->tahun_periode ? 'selected' : '' }}>
                                {{ $year->tahun_periode }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control mr-2" id="nama_dosen" name="nama_dosen" 
                           placeholder="Nama Dosen" value="{{ $selectedDosen }}">
                    <input type="text" class="form-control" id="nama_tendik" name="nama_tendik" 
                           placeholder="Nama Tendik" value="{{ $selectedTendik }}">
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm" id="table-periode">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Tahun Periode</th>
                        <th>Pelatihan</th>
                        <th>Sertifikasi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script>
    var datatable;
    $(document).ready(function() {
        // Initialize DataTable
        datatable = $('#table-periode').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('kelola_periode/list') }}",
                type: 'POST',
                data: function(d) {
                    d.tahun_periode = $('#tahun_periode').val();  // Filter tahun
                    d.nama_dosen = $('#nama_dosen').val();        // Filter dosen
                    d.nama_tendik = $('#nama_tendik').val();      // Filter tendik
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);  // Debugging error log
                    alert('An error occurred while fetching data!');
                }
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nama_lengkap' },
                { data: 'periode' },
                { data: 'pelatihan' },
                { data: 'sertifikasi' }
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html(index + 1);  // Menambahkan nomor urut di kolom pertama
            }
        });

        // Reload DataTable when filter is changed
        $('#tahun_periode, #nama_dosen, #nama_tendik').change(function() {
            datatable.ajax.reload();  // Reload DataTable after filter change
        });
    });
</script>
@endpush
