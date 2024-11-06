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
            position: relative;
        }
        .login-box, .card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-box {
            width: 360px;
            margin: 7% auto;
        }
        .card-header, .btn {
            border-radius: 20px;
        }
        /* Styles for the logos */
        .logo-top-left, .logo-top-right {
            position: absolute;
            top: 20px;
            width: 80px;
        }
        .logo-top-left {
            left: 20px;
        }
        .logo-top-right {
            right: 20px;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme Style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
    <!-- Logos in the top corners -->
    <img src="{{ asset('storage/photos/polinema.png') }}" alt="Logo Kiri" class="logo-top-left">
    <img src="{{ asset('storage/photos/jti.png') }}" alt="Logo Kanan" class="logo-top-right">

    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Register</b>Account</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a New User</p>
                <form method="POST" action="{{ url('register') }}" id="registration-form">
                    @csrf
                    <div class="input-group mb-3">
                        <select class="form-control" id="user_role" name="peran" required>
                            <option value="">- Select Role -</option>
                            <option value="admin">Admin</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Pimpinan">Pimpinan</option>
                            <!-- Add more roles as needed -->
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-layer-group"></span>
                            </div>
                        </div>
                        @error('peran')
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
        </div>
    </div>
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
       $(document).ready(function() {
           $("#registration-form").validate({
               rules: {
                   peran: { required: true },
                   username: { required: true, minlength: 4, maxlength: 20 },
                   password: { required: true, minlength: 5 },
                   terms: { required: true }
               },
               messages: {
                   peran: "Please select a user role.",
                   username: { required: "Please enter a username", minlength: "Must be at least 4 characters" },
                   password: { required: "Please provide a password", minlength: "Must be at least 5 characters" },
                   terms: "You must agree to the terms and conditions"
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
                       },
                       error: function(xhr) {
                           let errorMessage = xhr.responseJSON?.message || 'An error occurred';
                           Swal.fire({
                               icon: 'error',
                               title: 'Error Occurred',
                               text: errorMessage
                           });
                       }
                   });
                   return false;
               }
           });
       });
    </script>    
</body>
</html>
