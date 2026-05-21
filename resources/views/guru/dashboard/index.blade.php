@extends('adminlte::page')

@section('title', 'Dashboard Guru')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
    <!-- perangkat pembelajaran -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Perangkat Pembelajaran (% yang sudah selesai)</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/perangkat" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    

    <!-- dokumen adm lainnya -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>10 Dokumen<sup style="font-size: 20px"></sup></h3>

                <p>Dokumen Adm Lainnya</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="/dokumen-admin" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->    
</div>
<!-- TOP CARDS -->


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