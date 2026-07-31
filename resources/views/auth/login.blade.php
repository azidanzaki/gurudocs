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

        .login-card {
            width: 420px;
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
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .input-group-text {
            background: #fff;
            border-right: none;
            color: #1f331d;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            height: 52px;
            border-left: none;
            border-radius: 0 12px 12px 0 !important;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #1f331d;
        }

        .btn-login {
            height: 52px;
            border-radius: 12px;
            background: #1f331d;
            border: none;
            font-weight: 600;
            transition: .3s;
        }

        .btn-login:hover {
            background: #2c4f30;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(31, 51, 29, .25);
        }

        .extra {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .extra a {
            color: #1f331d;
            text-decoration: none;
            font-weight: 500;
        }

        .extra a:hover {
            text-decoration: underline;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #888;
        }

        .login-error {
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

            .login-card {
                width: 92%;
                padding: 30px;
            }

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

                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>

                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan Password" required>

                    <div class="input-group-append">

                        <span class="input-group-text" onclick="togglePassword()"
                            style="cursor:pointer;border-radius:0 12px 12px 0;">

                            <i class="fas fa-eye" id="eye"></i>

                        </span>

                    </div>

                </div>

            </div>

            <div class="extra">

                <div>
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>

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