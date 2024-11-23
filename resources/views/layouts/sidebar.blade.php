<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <style>
   /* Main Sidebar */
.main-sidebar {
    background-color: rgb(11, 11, 170); /* Warna sidebar biru */
    color: white; /* Warna teks putih */
    border-radius: 0 0px 0px 0; /* Kelengkungan hanya di sudut kanan */
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19); /* Efek 3D */
    width: 250px; /* Lebar sidebar */
    overflow-y: auto; /* Sidebar bisa di-scroll jika konten terlalu panjang */
}


      /* Styling Link di Sidebar */
      .nav-link {
          color: white; /* Warna link sidebar */
          border-radius: 10px; /* Kelengkungan pada link */
          padding: 10px 15px; /* Tambahkan padding untuk estetika */
          transition: all 0.3s ease; /* Animasi untuk hover */
      }
  
      .nav-link:hover {
          background-color: rgba(255, 255, 255, 0.2); /* Warna hover */
          color: white; /* Tetap putih saat hover */
          transform: translateY(-2px); /* Efek hover mengangkat */
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan efek 3D pada hover */
      }
  
      .nav-link.active {
          background-color: darkblue; /* Warna untuk link aktif */
          border-radius: 10px; /* Pastikan kelengkungan tetap terlihat */
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3); /* Tambahkan efek 3D untuk link aktif */
      }
  
      /* Styling Sidebar Item */
   
  
      .sidebar .nav-item .nav-link {
          border-radius: 10px; /* Memberikan lengkungan pada tombol */
          margin-bottom: 5px; /* Memberikan jarak antar item */
          color: white;
      }
  
      .sidebar .nav-item .nav-link.active {
          background-color: #007bff; /* Warna untuk menu aktif */
          color: white; /* Warna teks menu aktif */
      }
  
      .sidebar .nav-item .nav-link:hover {
          background-color: rgba(255, 255, 255, 0.2); /* Warna efek hover */
          color: white;
      }
  
      /* Styling Search Form */
      .sidebar .form-inline {
          padding: 10px;
      }
  
      .sidebar .form-control {
          border-radius: 15px; /* Lengkungan pada input search */
          background-color: #0056b3;
          color: white;
          border-color: #007bff;
      }
  
      .sidebar .btn-sidebar {
          border-radius: 15px; /* Kelengkungan pada tombol search */
          background-color: #0056b3;
          color: white;
          border-color: #007bff;
      }
  </style>
  
</head>
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
          <i class="nav-icon fas fa-users"></i>
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
            <a href="{{ url('/jenis_pelatihan') }}" class="nav-link {{ $activeMenu == 'jenisPelatihan' ? 'active' : '' }}">
              <p>Jenis Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor_pelatihan') }}" class="nav-link {{ $activeMenu == 'vendor_pelatihan' ? 'active' : '' }}">
              <p>Vendor Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/vendor_sertifikasi') }}" class="nav-link {{ $activeMenu == 'vendor_sertifikasi' ? 'active' : '' }}">
              <p>Vendor Sertifikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/mata_kuliah') }}" class="nav-link  {{ $activeMenu == 'mata_kuliah' ? 'active' : '' }}">
              <p>Kelola Mata Kuliah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/bidang_minat') }}" class="nav-link {{ $activeMenu == 'bidang_minat' ? 'active' : '' }}">
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
            <a href="{{ url('/riwayat_pelatihan') }}" class="nav-link {{ $activeMenu == 'riwayat_pelatihan' ? 'active' : '' }}">
              <p>Riwayat Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/riwayat_sertifikasi') }}" class="nav-link {{ $activeMenu == 'riwayat_sertifikasi' ? 'active' : '' }}">
              <p>Riwayat Sertfikasi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/daftar_pelatihan') }}" class="nav-link {{ $activeMenu == 'daftar_pelatihan' ? 'active' : '' }}">
              <p>Daftar Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/pengajuan_pelatihan') }}" class="nav-link {{ $activeMenu == 'pengajuan_pelatihan' ? 'active' : '' }}">
              <p>Pengajuan Pelatihan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/statistik_sertifikasi') }}" class="nav-link {{ $activeMenu == 'statistik' ? 'active' : '' }}">
              <p>Statistik Sertifikasi</p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="{{ url('/draft-surat-tugas') }}" class="nav-link {{ $activeMenu == 'draftSuratTugas' ? 'active' : '' }}">
              <p>Draft Surat Tugas</p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="{{ url('/kelola-periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
              <p>Kelola Periode</p>
            </a>
          </li>
        </ul>
      </li>

      <!-- Button Logout -->
      {{-- <li class="nav-item">
          <a href="#" class="nav-link" onclick="logout()">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
          </a>
      </li> --}}
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
</body>
</html>