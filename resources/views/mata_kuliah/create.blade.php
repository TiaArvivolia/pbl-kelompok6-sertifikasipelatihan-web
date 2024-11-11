@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Mata Kuliah</h1>

    <form action="{{ route('kelola-mata-kuliah.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="kode_mk">Kode Mata Kuliah</label>
            <input type="text" name="kode_mk" id="kode_mk" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nama_mk">Nama Mata Kuliah</label>
            <input type="text" name="nama_mk" id="nama_mk" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
