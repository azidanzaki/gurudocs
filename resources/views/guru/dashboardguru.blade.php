@extends('adminlte::page')

@section('title', 'Dashboard Guru')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<!-- TOP CARDS -->
<div class="row">

    <!-- Tugas -->
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>2 / 10</h3>
                <p>Tugas Semester</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-lines"></i>
            </div>
        </div>
    </div>

    <!-- Profil -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">

                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logomts.png') }}"
                         class="img-circle mr-3"
                         style="width:60px; height:60px; object-fit:cover;">

                    <div>
                        <h5 class="mb-0">Zakiah</h5>
                        <small class="text-muted">12345678890</small>
                    </div>
                </div>

                <a href="#" class="btn btn-sm btn-warning">
                    <i class="fas fa-pen"></i>
                </a>

            </div>
        </div>
    </div>

</div>

<!-- MAIN CONTENT -->
<div class="row">

    <!-- LEFT -->
    <div class="col-md-8">

        <!-- SEARCH -->
        <div class="card">
            <div class="card-body">

                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari Dokumen">
                    <div class="input-group-append">
                        <button class="btn btn-dark">Dokumen</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dokumen Yang Mungkin Kamu Butuhkan</h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped">

                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>RPP Quran Hadits</td>
                            <td>Quran Hadits IX</td>
                            <td><a href="#" class="text-success">Detail</a></td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="col-md-4">

        <!-- TASK -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tugas Dekat</h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm">

                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td>RPP Quran Hadits</td>
                            <td>26/09/2025</td>
                        </tr>
                    @endfor

                </table>
            </div>
        </div>

        <!-- RECENT -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dokumen Terbaru</h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm">

                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td>RPP Quran Hadits</td>
                            <td>IX</td>
                            <td>7:00</td>
                        </tr>
                    @endfor

                </table>
            </div>

        </div>

    </div>

</div>

@stop