<!DOCTYPE html>
<html lang="en">
<head>
     <style>
        body {
            background-image: url('storage/photos/register.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; 
      }

        .login-box, .card {
            background-color: rgba(255, 255, 255, 0.8); /* Set transparansi untuk card */
            border-radius: 20px; /* Lengkungan sudut lebih jelas */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek bayangan */
            overflow: hidden; /* Agar konten di dalam mengikuti lengkungan */
        }

        .login-box {
            width: 360px;
            margin: 7% auto;
        }

        .card-header, .btn {
            border-radius: 20px; /* Lengkungan pada header dan tombol */
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- iCheck Bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme Style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a New User</p>
                <form method="POST" action="{{ url('register') }}" id="registration-form">
                    @csrf
                    <div class="input-group mb-3">
                        <select class="form-control" id="user_level" name="level_id" required>
                            <option value="">- Select Level -</option>
                            @foreach ($level as $levelItem)
                                <option value="{{ $levelItem->level_id }}">{{ $levelItem->level_nama }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-layer-group"></span>
                            </div>
                        </div>
                        @error('level_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="user_name" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="full_name" name="nama" class="form-control" placeholder="Full Name" value="{{ old('nama') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span>
                            </div>
                        </div>
                        @error('nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="user_password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="termsAgree" name="terms" value="agree">
                                <label for="termsAgree">
                                    I agree to the <a href="#">terms and conditions</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 text-center">
                            <p>Already have an account? <a href="{{ url('login') }}">Log In</a></p>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery Validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#registration-form").validate({
                rules: {
                    level_id: {
                        required: true,
                    },
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error Occurred',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>