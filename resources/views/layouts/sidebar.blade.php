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

      <!-- Kategori Kelola Pengguna -->
      <li class="nav-header">Kategori Kelola Pengguna</li>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Kelola Pengguna
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ url('/jenis_pengguna') }}" class="nav-link {{ $activeMenu == 'jenis_pengguna' ? 'active' : '' }}">
              <p>Kelola Jenis Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/pengguna') }}" class="nav-link {{ $activeMenu == 'pengguna' ? 'active' : '' }}">
              <p>Kelola Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin') }}" class="nav-link {{ $activeMenu == 'admin' ? 'active' : '' }}">
              <p>Kelola Admin</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/dosen') }}" class="nav-link {{ $activeMenu == 'dosen' ? 'active' : '' }}">
              <p>Kelola Dosen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/tendik') }}" class="nav-link {{ $activeMenu == 'tendik' ? 'active' : '' }}">
              <p>Kelola Tendik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/pimpinan') }}" class="nav-link {{ $activeMenu == 'pimpinan' ? 'active' : '' }}">
              <p>Kelola Pimpinan</p>
            </a>
          </li>
        </ul>
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
            <a href="{{ url('/jenis-pelatihan') }}" class="nav-link {{ $activeMenu == 'jenisPelatihan' ? 'active' : '' }}">
              <p>Jenis Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor_pelatihan') }}" class="nav-link {{ $activeMenu == 'vendorpelatihan' ? 'active' : '' }}">
              <p>Vendor Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor_sertifikasi') }}" class="nav-link {{ $activeMenu == 'vendorSertifikasi' ? 'active' : '' }}">
              <p>Vendor Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/mata_kuliah') }}" class="nav-link  {{ $activeMenu == 'mataKuliah' ? 'active' : '' }}">
              <p>Kelola Mata Kuliah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/bidang_minat') }}" class="nav-link {{ $activeMenu == 'bidangMinat' ? 'active' : '' }}">
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
            <a href="{{ url('/daftar-sertifikasi') }}" class="nav-link {{ $activeMenu == 'daftarSertifikasi' ? 'active' : '' }}">
              <p>Daftar Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/daftar-pelatihan') }}" class="nav-link {{ $activeMenu == 'daftarPelatihan' ? 'active' : '' }}">
              <p>Daftar Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/draft-surat-tugas') }}" class="nav-link {{ $activeMenu == 'draftSuratTugas' ? 'active' : '' }}">
              <p>Draft Surat Tugas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/statistik-sertifikasi') }}" class="nav-link {{ $activeMenu == 'statistik' ? 'active' : '' }}">
              <p>Statistik Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/pelatihan-sertifikasi') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
              <p>Pelatihan & Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/kelola-periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
              <p>Kelola Periode</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/input_pelatihan') }}" class="nav-link {{ $activeMenu == 'inputPelatihan' ? 'active' : '' }}">
              <p>Input Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/input_sertifikasi') }}" class="nav-link {{ $activeMenu == 'inputSertifikasi' ? 'active' : '' }}">
              <p>Input Sertifikasi</p>
            </a>
          </li>
        </ul>
      </li>

      <!-- Button Logout -->
      <li class="nav-item">
          <a href="#" class="nav-link" onclick="logout()">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
          </a>
      </li>
    </ul>
  </nav>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function logout() {
  localStorage.removeItem('authToken');
  Swal.fire({
      icon: 'success',
      title: 'Logged Out',
      text: 'Anda telah berhasil logout!',
      showConfirmButton: false,
      timer: 1500
  }).then(() => {
      window.location.href = '{{ url('/logout') }}';
  });
}
</script>
