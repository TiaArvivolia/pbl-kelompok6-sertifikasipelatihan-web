<!-- resources/views/mata_kuliah/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Mata Kuliah</h1>

    <form action="{{ route('kelola-mata-kuliah.update', $mataKuliah->id_mata_kuliah) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="kode_mk">Kode Mata Kuliah</label>
            <input type="text" name="kode_mk" id="kode_mk" value="{{ $mataKuliah->kode_mk }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nama_mk">Nama Mata Kuliah</label>
            <input type="text" name="nama_mk" id="nama_mk" value="{{ $mataKuliah->nama_mk }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
