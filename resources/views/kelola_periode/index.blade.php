@extends('layouts.template')

@section('content')
<div class="container">

    {{-- Form Filter Tahun Periode --}}
    <form action="{{ route('kelola_periode.index') }}" method="GET">
        <div class="form-group">
            <label for="tahun_periode">Filter Tahun Periode</label>
            <select name="tahun_periode" id="tahun_periode" class="form-control" onchange="this.form.submit()">
                <option value="">Pilih Tahun</option>
                @foreach($yearsList as $year)
                    <option value="{{ $year->tahun_periode }}" 
                        {{ $year->tahun_periode == $selectedYear ? 'selected' : '' }}>
                        {{ $year->tahun_periode }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th>Periode</th>
                <th>Total Pelatihan</th>
                <th>Total Sertifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($periodeList as $periode)
                <tr>
                    <td>
                        @foreach($periode->riwayatPelatihan as $pelatihan)
                            {{ $pelatihan->pengguna->nama_lengkap }} <br>
                        @endforeach
                    </td>
                    <td>{{ $periode->tahun_periode }}</td>
                    <td>{{ $periode->riwayat_pelatihan_count }}</td>
                    <td>{{ $periode->riwayat_sertifikasi_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
