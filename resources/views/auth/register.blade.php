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

        .register-wrapper{
            width:100%;
            height:100vh;
            display:flex;
        }

        /* LEFT SIDE */
        .register-left{
            width:50%;
            background:#ffffff;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .register-card-custom{
            width:100%;
            max-width:460px;
            border:none;
            border-radius:18px;
            box-shadow:0 10px 35px rgba(0,0,0,0.08);
            overflow:hidden;
        }

        .register-card-body{
            padding:40px;
        }

        .logo-area{
            text-align:center;
            margin-bottom:25px;
        }

        .logo-circle{
            width:95px;
            height:95px;
            border-radius:50%;
            background:#1f331d;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            margin-bottom:15px;
            overflow:hidden;
        }

        .logo-circle img{
            width:75px;
            height:75px;
            object-fit:cover;
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
            width:50px;
            justify-content:center;
        }

        .form-control{
            height:48px;
            border-radius:0 8px 8px 0 !important;
        }

        .input-group{
            margin-bottom:18px;
        }

        .btn-register{
            background:#1f331d;
            border:none;
            height:48px;
            border-radius:10px;
            font-weight:600;
            transition:0.3s;
        }

        .btn-register:hover{
            background:#2f4d2b;
        }

        .info-box-register{
            margin-top:25px;
            background:#eef5ee;
            border-radius:12px;
            padding:15px;
            text-align:center;
            color:#4c5f4a;
            font-size:14px;
        }

        .login-link{
            margin-top:20px;
            text-align:center;
        }

        .login-link a{
            color:#1f331d;
            font-weight:600;
            text-decoration:none;
        }

        .login-link a:hover{
            text-decoration:underline;
        }

        /* RIGHT SIDE */
        .register-right{
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
            background:rgba(31,51,29,0.78);
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
            min-width:200px;
            backdrop-filter:blur(5px);
        }

        .feature-item i{
            font-size:22px;
            margin-bottom:10px;
        }

        /* RESPONSIVE */
        @media(max-width:992px){

            .register-right{
                display:none;
            }

            .register-left{
                width:100%;
            }

            body{
                overflow:auto;
            }
        }
    </style>
</head>

<body>

<div class="register-wrapper">

    <!-- LEFT -->
    <div class="register-left">

        <div class="card register-card-custom">
            <div class="card-body register-card-body">

                <div class="logo-area">

                    <div class="logo-circle">
                        <img src="{{ asset('images/logomts.png') }}">
                    </div>

                    <h2>GuruDocs</h2>
                    <p>MTsN 03 Rohul</p>

                </div>

                <p class="text-muted mb-4 text-center">
                    Daftarkan akun baru untuk menggunakan sistem
                </p>

                <form method="POST" action="{{ route('register') }}">
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

                    <!-- NAME -->
                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>

                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Masukkan Nama Lengkap"
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

                    <!-- CONFIRM PASSWORD -->
                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Konfirmasi Password"
                               required>

                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-success btn-block btn-register">
                        <i class="fas fa-user-plus mr-2"></i>
                        Register
                    </button>

                </form>

                <div class="login-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">
                        Login disini
                    </a>
                </div>

                <div class="info-box-register">
                    Sistem informasi dokumen administrasi guru berbasis web
                    untuk mempermudah pengelolaan perangkat pembelajaran.
                </div>

            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="register-right">

        <div class="overlay">

            <h1>GuruDocs</h1>

            <p>
                Platform digital modern untuk membantu guru dalam
                mengelola perangkat pembelajaran, dokumen administrasi,
                dan arsip sekolah secara efektif serta efisien.
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
                    <div>Edit, Download, dan Upload Dokumen</div>
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