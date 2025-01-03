<nav class="main-header navbar navbar-expand" style="background-color: #f9f9f9; padding: 10px 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Menu Toggle Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #003366;">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        
        <!-- Navigation Links -->
        {{-- <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">Home</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/more-info') }}" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">More Info</a>
        </li>
        <li class="nav-item">
            <a href="mailto:info@example.com" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">Email</a>
        </li> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto d-flex align-items-center">
        <!-- POLITEKNIK NEGERI MALANG text with logos -->
        <li class="nav-item d-flex align-items-center">
            <span class="font-weight-bold" style="font-size: 1.1rem; color: #003366;">Jurusan Teknologi Informasi POLINEMA</span>

            <!-- Logos -->
            {{-- <img src="{{ asset('storage/photos/polinema.png') }}" alt="Polinema Logo" class="img-fluid rounded-circle" style="width: 35px; height: 35px; margin-left: 15px;"> --}}
            {{-- <img src="{{ asset('storage/photos/jti.png') }}" alt="JTI Logo" class="img-fluid rounded-circle" style="width: 35px; height: 35px; margin-left: 10px;"> --}}
        </li>

<!-- User Info Dropdown Menu -->
<li class="nav-item dropdown ml-3 d-flex align-items-center">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <div class="d-flex align-items-center">
            <div class="mr-2" style="margin-top: -5px; margin-bottom: 5px;">
                @php
                    $userProfile = null;
                    // Tentukan model pengguna dan gambar profil sesuai dengan jenis pengguna
                    $pengguna = Auth::user(); // Ambil pengguna yang sedang login
                    if ($pengguna->dosen && $pengguna->dosen->gambar_profil) {
                        $userProfile = $pengguna->dosen->gambar_profil;
                    } elseif ($pengguna->tendik && $pengguna->tendik->gambar_profil) {
                        $userProfile = $pengguna->tendik->gambar_profil;
                    } elseif ($pengguna->admin && $pengguna->admin->gambar_profil) {
                        $userProfile = $pengguna->admin->gambar_profil;
                    } elseif ($pengguna->pimpinan && $pengguna->pimpinan->gambar_profil) {
                        $userProfile = $pengguna->pimpinan->gambar_profil;
                    }
                @endphp

                @if ($userProfile)
                    <img class="img-circle" 
                    src="{{ asset('storage/' . $userProfile) }}" 
                    alt="User profile picture"
                    style="width: 30px; height: 30px; object-fit: cover;">           
                @else
                    <i class="fas fa-user-circle" style="font-size: 30px; color: #ccc;"></i>
                @endif
            </div>

            <div class="d-flex flex-column">
            <div class="mr-2" style="margin-top: -5px; margin-bottom: 5px;">
                <span class="text-dark font-weight-bold" style="font-size: 14px;"> <!-- Adjusted font size -->
                    {{ $pengguna->username ?? 'Guest' }}
                </span>
                <span class="text-muted" style="font-size: 12px;"> <!-- Adjusted font size -->
                    ({{ $pengguna->jenisPengguna->nama_jenis_pengguna ?? 'No Role' }})
                </span>
            </div>
            </div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="{{ route('profile') }}" class="dropdown-item">
            <i class="fas fa-user-circle mr-2"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ url('logout') }}" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</li>


    </ul>
</nav>

