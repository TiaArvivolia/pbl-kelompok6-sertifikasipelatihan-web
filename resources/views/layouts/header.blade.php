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
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">Home</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/more-info') }}" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">More Info</a>
        </li>
        <li class="nav-item">
            <a href="mailto:info@example.com" class="nav-link text-dark font-weight-bold" style="color: #003366; margin-left: 10px;">Email</a>
        </li>
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
                    @if (isset(Auth::user()->profile_picture) && Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                            alt="User Avatar" 
                            class="img-circle" 
                            style="width: 30px; height: 30px;">
                    @else
                        <i class="fas fa-user-circle" style="font-size: 30px; color: #ccc;"></i>
                    @endif
                    <span class="ml-2 text-dark font-weight-bold">
                        {{ Auth::user()->username ?? 'Guest' }} 
                        ({{ Auth::user()->level->level_nama ?? 'No Role' }})
                    </span>
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
