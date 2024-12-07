{{-- resources/views/statistik_sertifikasi.blade.php --}}

@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Statistik Sertifikasi Dosen</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Dosen</th>
                <th>Jumlah Sertifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dosen as $d)
                <tr>
                    <td>{{ $d->pengguna->nama }}</td>
                    <td>{{ $d->pengguna->riwayatSertifikasi_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>Statistik Sertifikasi Tendik</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Tendik</th>
                <th>Jumlah Sertifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tendik as $t)
                <tr>
                    <td>{{ $t->pengguna->nama }}</td>
                    <td>{{ $t->pengguna->riwayatSertifikasi_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
