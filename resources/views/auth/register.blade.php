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

        .register-box {
            width: 360px;
            margin: 10% auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .register-box:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border-radius: 20px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 10px 0;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

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

        .error-text {
            font-size: 12px;
        }

        .login-link {
            color: #007bff;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Pengguna</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">
    <img src="{{ asset('storage/photos/polinema.png') }}" alt="Logo Kiri" class="logo-top-left">
    <img src="{{ asset('storage/photos/jti.png') }}" alt="Logo Kanan" class="logo-top-right">

    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="{{ url('/') }}" class="h1"><b>SkillHub TI</b></a>
            </div>
            <div class="card-body">
                <form action="{{ url('register') }}" method="POST" id="form-register">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="input-label">Username</label>
                        <div class="input-group">
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="input-label">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap">
                        <small id="error-nama_lengkap" class="error-text text-danger"></small>
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
                    <div class="mb-3">
                        <label for="jenis_pengguna" class="input-label">Jenis Pengguna</label>
                        <select id="jenis_pengguna" name="jenis_pengguna" class="form-control">
                            <option value="" selected disabled>Pilih Jenis Pengguna</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_jenis_pengguna }}">{{ $role->nama_jenis_pengguna }}</option>
                            @endforeach
                        </select>
                        <small id="error-jenis_pengguna" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 text-center">
                            <p>Sudah punya akun? <a href="{{ url('login') }}" class="login-link">Login</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
