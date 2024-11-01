<nav class="main-header navbar navbar-expand" style="background-color: #f2f2f2;">
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
    <ul class="navbar-nav ml-auto">
        <!-- POLITEKNIK NEGERI MALANG text with logos -->
        <li class="nav-item d-flex align-items-center">
           
            <!-- Text -->
            <span class="text-dark font-weight-bold" style="font-size: 1.1rem; color: #003366;">POLITEKNIK NEGERI MALANG</span>

            <!-- Logo 1 -->
            <img src="{{ asset('storage/photos/polinema.png') }}" alt="Logo 1" class="img-fluid" style="width: 40px; height: 40px; margin-right: 8px;">

            <!-- Logo 2 -->
            <img src="{{ asset('storage/photos/jti.png') }}" alt="Logo 2" class="img-fluid" style="width: 40px; height: 40px; margin-left: 8px;">
        </li>

        <!-- Fullscreen Toggle Button -->
        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0);" onclick="toggleFullscreen()" title="Toggle Fullscreen" style="color: #003366;">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
