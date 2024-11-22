@extends('layouts.template')

@section('title', 'Profile')

@section('content')
@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header text-center">
                    <h4 class="card-title">Your Profile Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($pengguna->dosen && $pengguna->dosen->gambar_profil)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $pengguna->dosen->gambar_profil) }}" 
                                 alt="User profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif ($pengguna->tendik && $pengguna->tendik->gambar_profil)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $pengguna->tendik->gambar_profil) }}" 
                                 alt="User profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif ($pengguna->admin && $pengguna->admin->gambar_profil)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $pengguna->admin->gambar_profil) }}" 
                                 alt="User profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif ($pengguna->pimpinan && $pengguna->pimpinan->gambar_profil)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset('storage/' . $pengguna->pimpinan->gambar_profil) }}" 
                                 alt="User profile picture"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #ccc;"></i>
                        @endif
                    </div>
                    
                    <h5 class="profile-username text-center" style="color: #333;">{{ $pengguna->username }}</h5>
                    <p class="text-muted text-center">{{ $pengguna->jenisPengguna->nama_jenis_pengguna ?? 'N/A' }}</p> <!-- Access related level/jenisPengguna -->

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <b>Nama:</b> 
                            <span class="float-right">
                                @if ($pengguna->dosen)
                                    {{ $pengguna->dosen->nama_lengkap }}
                                @elseif ($pengguna->tendik)
                                    {{ $pengguna->tendik->nama_lengkap }}
                                @elseif ($pengguna->admin)
                                    {{ $pengguna->admin->nama_lengkap }}
                                @elseif ($pengguna->pimpinan)
                                    {{ $pengguna->pimpinan->nama_lengkap }}
                                @else
                                    Tidak Tersedia
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Username:</b> <span class="float-right">{{ $pengguna->username }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Level:</b> <span class="float-right">{{ $pengguna->jenisPengguna->nama_jenis_pengguna ?? 'N/A' }}</span> <!-- Adjust the field name accordingly -->
                        </li>
                    </ul>

                    <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <label for="gambar_profil">Upload New Profile Picture:</label>
                        <input type="file" name="gambar_profil" class="form-control" accept="image/*" required>
                        <button type="submit" class="btn btn-primary mt-2 w-100">Upload</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Profile Information Card -->
        <div class="col-md-6 col-lg-4 mt-4 mt-md-0">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header text-center">
                    <h4 class="card-title">Update Username</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- <div class="form-group">
                            <label for="nama_lengkap">Nama:</label>
                            <input type="text" name="nama_lengkap" class="form-control" 
                                   value="
                                       @if ($pengguna->dosen)
                                           {{ $pengguna->dosen->nama_lengkap }}
                                       @elseif ($pengguna->tendik)
                                           {{ $pengguna->tendik->nama_lengkap }}
                                       @elseif ($pengguna->admin)
                                           {{ $pengguna->admin->nama_lengkap }}
                                       @elseif ($pengguna->pimpinan)
                                           {{ $pengguna->pimpinan->nama_lengkap }}
                                       @else
                                           Tidak Tersedia
                                       @endif
                                   " readonly>
                        </div> --}}
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" class="form-control" value="{{ $pengguna->username }}" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Update Username</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="col-md-6 col-lg-4 mt-4 mt-md-0">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-header text-center">
                    <h4 class="card-title">Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.changePassword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="password">New Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password:</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
