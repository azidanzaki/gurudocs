<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GuruDocs</title>

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #16351f, #285d38, #3c7a52);
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: rgba(255, 255, 255, .12);
            border-radius: 50%;
            top: -120px;
            left: -120px;
            filter: blur(40px);
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            bottom: -120px;
            right: -120px;
            filter: blur(40px);
        }

        .register-card {
            width: 440px;
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
            position: relative;
            z-index: 5;
            animation: fadeUp .7s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 90px;
            height: 90px;
            background: #eef5ee;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            margin-bottom: 20px;
        }

        .logo img {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        h2 {
            text-align: center;
            color: #1f331d;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .input-group {
            border: 2px solid #ced4da;
            border-radius: 12px;
            overflow: hidden;
            transition: .25s;
        }

        .input-group:hover {
            transform: translateY(-2px);
        }

        .input-group-text {
            border: none;
            background: #fff;
            color: #1f331d;
        }

        .form-control {
            border: none;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #1f331d;
        }

        .input-group:focus-within {
            border-color: #1f331d;
            box-shadow: 0 0 0 .2rem rgba(40, 167, 69, .15);
        }

        .input-group:focus-within .input-group-text {
            color: #2f7d46;
        }

        /* Password */
        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #777;
            cursor: pointer;
            transition: .25s;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #2f7d46;
        }

        .btn-register {
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #1f331d;
            font-weight: 600;
            transition: .3s;
        }

        .btn-register:hover {
            background: #2c4f30;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(31, 51, 29, .25);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #1f331d;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #888;
        }

        .alert-danger {
            border-radius: 12px;
            font-size: 14px;
        }

        .register-error {
            background: #fdecec;
            color: #b42318;
            border: 1px solid #f5c2c7;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        @media(max-width:500px) {

            .register-card {
                width: 92%;
                padding: 30px;
            }

        }
    </style>
</head>

<body>

    <div class="register-card">

        <div class="logo">
            <img src="{{ asset('images/logomts.png') }}">
        </div>

        <h2>GuruDocs</h2>

        <p class="subtitle">
            MTsN 03 Rokan Hulu
            <br>
            Daftarkan akun baru untuk menggunakan sistem.
        </p>

        @if ($errors->any())
            <div class="register-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NIP -->
            <div class="form-group">
                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>

                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP"
                        value="{{ old('nip') }}" required>

                </div>
            </div>

            <!-- NAME -->
            <div class="form-group">
                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>

                    <input type="text" class="form-control" name="name" placeholder="Masukkan Nama Lengkap"
                        value="{{ old('name') }}" required>

                </div>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <div class="password-wrapper">

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>

                        <input type="password" class="form-control password-input" id="password" name="password"
                            placeholder="Masukkan Password" required>

                    </div>

                    <button type="button" class="password-toggle" onclick="togglePassword('password','eye1')">

                        <i class="fas fa-eye" id="eye1"></i>

                    </button>

                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">
                <div class="password-wrapper">

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>

                        <input type="password" class="form-control password-input" id="password_confirmation"
                            name="password_confirmation" placeholder="Konfirmasi Password" required>

                    </div>

                    <button type="button" class="password-toggle"
                        onclick="togglePassword('password_confirmation','eye2')">

                        <i class="fas fa-eye" id="eye2"></i>

                    </button>

                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block btn-register">

                <i class="fas fa-user-plus mr-2"></i>

                Register

            </button>

        </form>

        <div class="login-link">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login disini</a>
        </div>

        <div class="footer-text">
            Sistem Informasi Dokumen Administrasi Guru
            <br>
            © {{ date('Y') }} GuruDocs
        </div>

    </div>

    <script>

        function togglePassword(inputId, eyeId) {

            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);

            if (input.type === 'password') {

                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');

            } else {

                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');

            }

        }

    </script>

</body>

</html>