<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            background-image: url('storage/photos/login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            font-family: 'Source Sans Pro', sans-serif;
        }

        /* Styling for the login box */
        .login-box {
            width: 360px;
            margin: 10% auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px; /* Slightly rounder corners for a softer look */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Softer shadow for a more natural depth */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Smooth transition effects */
        }

        /* Hover effect to scale the login box */
        .login-box:hover {
            transform: scale(1.05); /* Slightly increase size */
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); /* Enhance the shadow on hover */
        }

        /* Styling for the form input fields */
        .form-control {
            border-radius: 20px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transition for focus */
        }

        .form-control:focus {
            border-color: #007bff; /* Highlight border on focus */
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25); /* Add a soft glow effect */
        }

        /* Button hover effect */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 10px 0;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s, border-color 0.3s; /* Smooth transition */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }

        /* Logo at the top left and right */
        .logo-top-left,
        .logo-top-right {
            position: absolute;
            top: 20px;
            width: 80px;
            z-index: 100;
        }

        .logo-top-left {
            left: 20px;
        }

        .logo-top-right {
            right: 20px;
        }


        .card-header {
            text-align: center;
            background-color: transparent;
            border: none;
            margin-bottom: 20px;
        }

        .card-header .h1 {
            font-size: 2em;
            font-weight: 700;
            color: #007bff;
        }

        .input-label {
            color: #333;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        

        .row {
            margin-top: 20px;
        }

        .icheck-primary label {
            font-size: 14px;
        }

        .error-text {
            font-size: 12px;
        }


        /* Styling for the "Register" link */
        .register-link {
            color: #007bff;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pengguna</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <!-- Logo at top left and right -->
    <img src="{{ asset('storage/photos/polinema.png') }}" alt="Logo Kiri" class="logo-top-left">
    <img src="{{ asset('storage/photos/jti.png') }}" alt="Logo Kanan" class="logo-top-right">

    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="{{ url('/') }}" class="h1"><b>Login</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="input-label">Username</label>
                        <div class="input-group">
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="input-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 text-center">
                            <p>Belum punya akun? <a href="{{ url('register') }}" class="register-link">Registrasi</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
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
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect;
                                });
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>

</html>
