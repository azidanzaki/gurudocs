<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - GuruDocs</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="hold-transition register-page" style="background:#1f331d;">

<div class="register-box">

    <div class="register-logo text-white">
        <b>GuruDocs</b><br>
        <small>MTsN 03 Rohul</small>
    </div>

    <div class="card">
        <div class="card-body register-card-body">

            <p class="login-box-msg">Daftar akun baru</p>

            <form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- NIP -->
    <div class="input-group mb-3">
        <input type="text"
               name="nip"
               class="form-control"
               placeholder="NIP"
               required>

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-id-card"></span>
            </div>
        </div>
    </div>

    <!-- NAME -->
    <div class="input-group mb-3">
        <input type="text"
               name="name"
               class="form-control"
               placeholder="Nama"
               required>

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>

    <!-- PASSWORD -->
    <div class="input-group mb-3">
        <input type="password"
               name="password"
               class="form-control"
               placeholder="Password"
               required>

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <!-- CONFIRM PASSWORD -->
    <div class="input-group mb-3">
        <input type="password"
               name="password_confirmation"
               class="form-control"
               placeholder="Konfirmasi Password"
               required>

        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <!-- BUTTON -->
    <button type="submit" class="btn btn-success btn-block">
        Register
    </button>

</form>

            <p class="mt-3 mb-0 text-center">
                <a href="{{ route('login') }}">Sudah punya akun? Login</a>
            </p>

        </div>
    </div>

</div>

<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>