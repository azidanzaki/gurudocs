<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - GuruDocs</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="hold-transition login-page" style="background:#1f331d;">

<div class="login-box">

    <div class="login-logo text-white">
        <b>GuruDocs</b><br>
        <small>MTsN 03 Rohul</small>
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">Login untuk masuk sistem</p>

            <form method="POST" action="{{ route('login') }}">
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

    <!-- REMEMBER -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
        </div>
    </div>

    <!-- BUTTON -->
    <button type="submit" class="btn btn-success btn-block">
        Login
    </button>

</form>

        </div>
    </div>

</div>

<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>