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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            margin:0;
            padding:0;
            background:#f4f6f9;
            overflow:hidden;
        }

        .login-wrapper{
            width:100%;
            height:100vh;
            display:flex;
        }

        /* LEFT SIDE */
        .login-left{
            width:50%;
            background:#ffffff;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .login-card-custom{
            width:100%;
            max-width:430px;
            border:none;
            border-radius:18px;
            box-shadow:0 10px 35px rgba(0,0,0,0.08);
            overflow:hidden;
        }

        .login-card-body{
            padding:40px;
        }

        .logo-area{
            text-align:center;
            margin-bottom:25px;
        }

        .logo-circle{
            width:90px;
            height:90px;
            border-radius:50%;
            background:#1f331d;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            font-size:38px;
            margin-bottom:15px;
        }

        .logo-area h2{
            font-weight:700;
            color:#1f331d;
            margin-bottom:5px;
        }

        .logo-area p{
            color:#777;
            font-size:14px;
        }

        .input-group-text{
            background:#1f331d;
            color:white;
            border:none;
        }

        .form-control{
            height:48px;
            border-radius:0 8px 8px 0 !important;
        }

        .input-group{
            margin-bottom:18px;
        }

        .btn-login{
            background:#1f331d;
            border:none;
            height:48px;
            border-radius:10px;
            font-weight:600;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#2f4d2b;
        }

        .info-box-login{
            margin-top:25px;
            background:#eef5ee;
            border-radius:12px;
            padding:15px;
            text-align:center;
            color:#4c5f4a;
            font-size:14px;
        }

        /* RIGHT SIDE */
        .login-right{
            width:50%;
            background:url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1400&auto=format&fit=crop') center center;
            background-size:cover;
            position:relative;
        }

        .overlay{
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(31,51,29,0.75);
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            color:white;
            text-align:center;
            padding:40px;
        }

        .overlay h1{
            font-size:42px;
            font-weight:700;
            margin-bottom:15px;
        }

        .overlay p{
            max-width:500px;
            line-height:1.8;
            color:#f1f1f1;
        }

        .feature-box{
            margin-top:30px;
            display:flex;
            gap:20px;
            flex-wrap:wrap;
            justify-content:center;
        }

        .feature-item{
            background:rgba(255,255,255,0.12);
            padding:15px 20px;
            border-radius:12px;
            min-width:180px;
            backdrop-filter:blur(5px);
        }

        .feature-item i{
            font-size:22px;
            margin-bottom:10px;
        }

        /* RESPONSIVE */
        @media(max-width:992px){

            .login-right{
                display:none;
            }

            .login-left{
                width:100%;
            }

            body{
                overflow:auto;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-left">

        <div class="card login-card-custom">
            <div class="card-body login-card-body">

                <div class="logo-area">
                    <div class="logo-circle">
                        <img src="{{ asset('images/logomts.png') }}"
                            style="width:70px; height:70px; object-fit:cover;">
                    </div>

                    <h2>GuruDocs</h2>
                    <p>MTsN 03 Rohul</p>
                </div>

                <p class="text-muted mb-4 text-center">
                    Silahkan login menggunakan akun anda
                </p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- NIP -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>

                        <input type="text"
                               name="nip"
                               class="form-control"
                               placeholder="Masukkan NIP"
                               required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>

                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Masukkan Password"
                               required>
                    </div>

                    <!-- REMEMBER -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>

                        <a href="#" class="text-success">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-success btn-block btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </button>

                </form>

                <div class="info-box-login">
                    Sistem informasi dokumen administrasi guru berbasis web
                    untuk mempermudah pengelolaan perangkat pembelajaran.
                </div>

            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="login-right">

        <div class="overlay">

            <h1>GuruDocs</h1>

            <p>
                Platform digital untuk membantu guru dalam mengelola
                perangkat pembelajaran, dokumen administrasi,
                serta arsip sekolah secara mudah dan cepat.
            </p>

            <div class="feature-box">

                <div class="feature-item">
                    <i class="fas fa-file-alt"></i>
                    <div>Perangkat Pembelajaran</div>
                </div>

                <div class="feature-item">
                    <i class="fas fa-book"></i>
                    <div>Dokumen Administrasi</div>
                </div>

                <div class="feature-item">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div>Edit, Download, Dan Upload Dokumen</div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>