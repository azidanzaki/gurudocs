<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GuruDocs</title>

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

        /* Background Blur */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
        }

        body::before {
            width: 450px;
            height: 450px;
            background: rgba(255, 255, 255, .12);
            top: -120px;
            left: -120px;
        }

        body::after {
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, .08);
            bottom: -120px;
            right: -120px;
        }

        /* Card */
        .login-card {
            width: 420px;
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
            position: relative;
            z-index: 2;
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

        /* Logo */
        .logo {
            width: 90px;
            height: 90px;
            margin: auto auto 20px;
            border-radius: 50%;
            background: #eef5ee;
            display: flex;
            justify-content: center;
            align-items: center;
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
            margin-bottom: 30px;
        }

        /* Form */
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
        }

        .input-group-prepend .input-group-text {
            border-right: none;
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
            border-color: #1f331d;
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

        /* Button */
        .btn-login {
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #1f331d;
            font-weight: 600;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2c4f30;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(31, 51, 29, .25);
        }

        /* Footer */
        .extra {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .extra a {
            color: #1f331d;
            font-weight: 500;
            text-decoration: none;
        }

        .extra a:hover {
            text-decoration: underline;
        }

        .footer-text {
            margin-top: 25px;
            text-align: center;
            color: #888;
            font-size: 13px;
        }

        /* Error */
        .login-error {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 12px 15px;
            border: 1px solid #f5c2c7;
            border-radius: 12px;
            background: #fdecec;
            color: #b42318;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width:500px) {

            .login-card {
                width: 92%;
                padding: 30px;
            }

        }

        .extra {
            margin: 20px 0;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .remember-me input {
            display: none;
        }

        .checkmark {
            width: 24px;
            height: 24px;
            border: 2px solid #d0d5dd;
            border-radius: 8px;
            background: #fff;

            display: flex;
            justify-content: center;
            align-items: center;

            transition: .3s;
        }

        .checkmark i {
            color: #fff;
            font-size: 12px;
            transform: scale(0);
            transition: .25s;
        }

        .remember-me:hover .checkmark {
            border-color: #2f7d46;
        }

        .remember-me input:checked+.checkmark {
            background: #1f331d;
            border-color: #1f331d;
            box-shadow: 0 8px 18px rgba(31, 51, 29, .22);
            transform: scale(1.08);
        }

        .remember-me input:checked+.checkmark i {
            transform: scale(1);
        }
    </style>

</head>

<body>

    <div class="login-card">

        <div class="logo">
            <img src="{{ asset('images/logomts.png') }}">
        </div>

        <h2>GuruDocs</h2>

        <p class="subtitle">
            MTsN 03 Rokan Hulu
            <br>
            Silakan login menggunakan akun Anda.
        </p>
        @if ($errors->any())
            <div class="login-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">

                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>

                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP" required>

                </div>

            </div>

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

                    <button type="button" class="password-toggle" onclick="togglePassword()">

                        <i class="fas fa-eye" id="eye"></i>

                    </button>

                </div>
            </div>

            <div class="extra">

                <label class="remember-me">

                    <input type="checkbox" id="remember" checked>

                    <span class="checkmark">
                        <i class="fas fa-check"></i>
                    </span>

                    <span>Remember Me</span>

                </label>

            </div>

            <button class="btn btn-success btn-block btn-login">

                <i class="fas fa-sign-in-alt mr-2"></i>

                Login

            </button>

        </form>

        <div class="footer-text">
            Sistem Informasi Dokumen Administrasi Guru
            <br>
            © {{ date('Y') }} GuruDocs
        </div>

    </div>

    <script>

        function togglePassword() {

            const password = document.getElementById("password");
            const eye = document.getElementById("eye");

            if (password.type === "password") {

                password.type = "text";
                eye.classList.remove("fa-eye");
                eye.classList.add("fa-eye-slash");

            } else {

                password.type = "password";
                eye.classList.remove("fa-eye-slash");
                eye.classList.add("fa-eye");

            }

        }

    </script>

</body>

</html>