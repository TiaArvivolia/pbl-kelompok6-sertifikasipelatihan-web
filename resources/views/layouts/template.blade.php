<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'PWL_POS')}}</title>

  <meta name="csrf-token" content="{{csrf_token()}}"> 
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css')}}">

  <style>
    .main-sidebar {
      background-color: rgb(11, 11, 170); /* Ubah warna sidebar menjadi biru */
      color: white; /* Mengubah warna teks menjadi putih */
    }
    .nav-link {
      color: white; /* Mengatur warna link sidebar */
    }
    .nav-link.active {
      background-color: darkblue; /* Ubah warna untuk link aktif */
    }
  </style>

  @stack('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  @include('layouts.header')

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url ('/profile') }}" class="brand-link">
      <img src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Kelompok 6 - PWL</span>
    </a>

    @include('layouts.sidebar')
  </aside>

  <div class="content-wrapper">
    @include('layouts.breadcrumb')

    <section class="content">
      @yield('content')
    </section>
  </div>

  @include('layouts.footer')
</div>

<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{asset('adminlte/dist/js/adminlte.min.js')}}"></script>

<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>
@stack('js')
</body>
</html>
