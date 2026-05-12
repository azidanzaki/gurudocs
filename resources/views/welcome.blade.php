<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GuruDocs - Welcome</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="hold-transition layout-top-nav">

<div class="wrapper">

    <!-- NAVBAR -->
    <nav class="main-header navbar navbar-expand-md navbar-dark" style="background:#1f331d;">
        <div class="container">

            <a href="#" class="navbar-brand">
                <img src="{{ asset('images/logomts.png') }}" class="brand-image img-circle" style="opacity:.8">
                <span class="brand-text font-weight-light">GuruDocs</span>
            </a>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link text-white">Register</a>
                </li>
            </ul>

        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="content-wrapper" style="background:#f4f6f9;">

        <div class="content-header text-center py-5">
            <div class="container">

                <img src="{{ asset('images/logomts.png') }}"
                     style="width:120px;height:120px;"
                     class="mb-3">

                <h1 class="font-weight-bold">GuruDocs</h1>
                <p class="text-muted">Sistem Informasi Dokumen Guru</p>

                <p class="text-secondary">
                    MTsN 03 Rokan Hulu
                </p>

                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg mr-2">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg">
                        Daftar
                    </a>
                </div>

            </div>
        </div>

        <!-- FEATURE -->
        <div class="container pb-5">

            <div class="row text-center">

                <div class="col-md-4">
                    <div class="card p-3">
                        <i class="fas fa-file-alt fa-3x text-success mb-2"></i>
                        <h5>Dokumen Guru</h5>
                        <p class="text-muted">RPP, Silabus, Prota, Promes</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3">
                        <i class="fas fa-bell fa-3x text-warning mb-2"></i>
                        <h5>Notifikasi</h5>
                        <p class="text-muted">Update tugas & dokumen terbaru</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3">
                        <i class="fas fa-user-shield fa-3x text-primary mb-2"></i>
                        <h5>Akses Guru</h5>
                        <p class="text-muted">Login khusus guru & admin</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="main-footer text-center">
        <strong>GuruDocs</strong> - MTsN 03 Rokan Hulu
    </footer>

</div>

<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>