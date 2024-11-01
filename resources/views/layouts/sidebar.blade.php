<div class="sidebar">
  <!-- SidebarSearch Form -->
  <div class="form-inline mt-2">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" 
            style="background-color: #0056b3; color: white; border-color: #007bff; border-radius: 15px 0 0 15px;">
        <div class="input-group-append">
            <button class="btn btn-sidebar" style="background-color: #0056b3; color: white; border-color: #007bff; border-radius: 0 15px 15px 0;">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>  
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>

      <!-- Kategori Umum -->
      <li class="nav-header">Kategori Umum</li>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Kelola Umum
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ url('/kelola-pengguna') }}" class="nav-link">
              <p>Kelola Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/jenis-pelatihan') }}" class="nav-link">
              <p>Jenis Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor-pelatihan') }}" class="nav-link">
              <p>Vendor Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor-sertifikasi') }}" class="nav-link">
              <p>Vendor Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/kelola-mata-kuliah') }}" class="nav-link">
              <p>Kelola Mata Kuliah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/kelola-bidang-minat') }}" class="nav-link">
              <p>Kelola Bidang Minat</p>
            </a>
          </li>
        </ul>
      </li>

      <!-- Kategori Kegiatan -->
      <li class="nav-header">Kategori Kegiatan</li>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-calendar"></i>
          <p>
            Kegiatan
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ url('/daftar-sertifikasi') }}" class="nav-link">
              <p>Daftar Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/daftar-pelatihan') }}" class="nav-link">
              <p>Daftar Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/draft-surat-tugas') }}" class="nav-link">
              <p>Draft Surat Tugas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/statistik-sertifikasi') }}" class="nav-link">
              <p>Statistik Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/pelatihan-sertifikasi') }}" class="nav-link">
              <p>Pelatihan & Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/kelola-periode') }}" class="nav-link">
              <p>Kelola Periode</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/input-pelatihan') }}" class="nav-link">
              <p>Input Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/input-sertifikasi') }}" class="nav-link">
              <p>Input Sertifikasi</p>
            </a>
          </li>
        </ul>
      </li>

      <!-- Button Logout -->
      <li class="nav-item mt-5">
        <a href="#" class="nav-link" onclick="logout()">
          <i class="nav-icon fas fa-sign-out-alt"></i>
          <p>Logout</p>
        </a>
      </li>
    </ul>
  </nav>
</div>
