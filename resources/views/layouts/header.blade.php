<nav class="main-header navbar navbar-expand" style="background-color: #f2f2f2; padding: 10px 20px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Menu Toggle Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #003366;">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        
        <!-- Home, More Info, and Email Links -->
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link" style="color: #003366;">Home</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/more-info') }}" class="nav-link" style="color: #003366;">More Info</a>
        </li>
        <li class="nav-item">
            <a href="mailto:info@example.com" class="nav-link" style="color: #003366;">Email</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto d-flex align-items-center">
        <!-- POLITEKNIK NEGERI MALANG text with logos -->
        <li class="nav-item d-flex align-items-center">
            <!-- Text -->
            <span class="text-dark font-weight-bold" style="font-size: 1.2rem; color: #003366;">Jurusan Teknologi Informasi POLINEMA</span>

            <!-- Logo 1 -->
            <img src="{{ asset('storage/photos/polinema.png') }}" alt="Logo 1" class="img-fluid" style="width: 35px; height: 35px; margin-left: 10px;">

            <!-- Logo 2 -->
            <img src="{{ asset('storage/photos/jti.png') }}" alt="Logo 2" class="img-fluid" style="width: 35px; height: 35px; margin-left: 10px;">
        </li>
    </ul>
</nav>
