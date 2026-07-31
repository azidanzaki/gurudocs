@extends('adminlte::page')

@section('title', 'Dashboard Guru')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold text-dark">
                <i class="fas fa-home mr-2 text-primary"></i>
                Dashboard Guru
            </h1>
            <p class="text-muted mb-0">
                Ringkasan aktivitas dan perangkat pembelajaran Anda
            </p>
        </div>
    </div>
@stop


@section('content')


<style>

.dashboard-card {
    border-radius: 15px;
    overflow: hidden;
    transition: .3s;
    border: none;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
}


.icon-dashboard {
    position:absolute;
    right:20px;
    top:20px;
    font-size:65px;
    opacity:.25;
}


.bg-gradient-info-custom {
    background: linear-gradient(135deg,#17a2b8,#0dcaf0);
}

.bg-gradient-success-custom {
    background: linear-gradient(135deg,#28a745,#20c997);
}

.bg-gradient-warning-custom {
    background: linear-gradient(135deg,#ffc107,#fd7e14);
}


.welcome-card {
    border-radius:15px;
    background:white;
}


</style>



<div class="row">


    {{-- Repository --}}
    <div class="col-lg-4 col-md-6">

        <div class="small-box dashboard-card bg-gradient-info-custom text-white shadow">

            <div class="inner p-4">

                <h2 class="font-weight-bold">
                    {{ $totalRepository }}
                </h2>

                <p class="mb-0">
                    Kegiatan Repository
                </p>

            </div>


            <div class="icon-dashboard">
                <i class="fas fa-images"></i>
            </div>


            <a href="{{ route('guru.repository') }}"
               class="small-box-footer">

                Kelola Repository
                <i class="fas fa-arrow-right ml-2"></i>

            </a>

        </div>

    </div>



    {{-- Selesai --}}
    <div class="col-lg-4 col-md-6">

        <div class="small-box dashboard-card bg-gradient-success-custom text-white shadow">

            <div class="inner p-4">

                <h2 class="font-weight-bold">
                    {{ $totalSelesai }}
                </h2>

                <p class="mb-0">
                    Perangkat Selesai
                </p>

            </div>


            <div class="icon-dashboard">
                <i class="fas fa-check-circle"></i>
            </div>


            <a href="{{ route('guru.perangkat.history') }}"
               class="small-box-footer">

                Lihat History
                <i class="fas fa-arrow-right ml-2"></i>

            </a>


        </div>

    </div>




    {{-- Draft --}}
    <div class="col-lg-4 col-md-6">

        <div class="small-box dashboard-card bg-gradient-warning-custom text-white shadow">

            <div class="inner p-4">

                <h2 class="font-weight-bold">
                    {{ $totalDraft }}
                </h2>


                <p class="mb-0">
                    Perangkat Tertunda
                </p>


            </div>


            <div class="icon-dashboard">
                <i class="fas fa-edit"></i>
            </div>



            <a href="{{ route('guru.perangkat.index') }}"
               class="small-box-footer text-white">


                Lanjutkan Pengerjaan
                <i class="fas fa-arrow-right ml-2"></i>


            </a>


        </div>


    </div>


</div>




{{-- Welcome Card --}}

<div class="card welcome-card shadow-sm mt-4">


    <div class="card-body p-4">


        <div class="row align-items-center">


            <div class="col-md-8">


                <h3 class="font-weight-bold">

                    Selamat Datang,
                    {{ Auth::user()->name }}

                    👋

                </h3>


                <p class="text-muted mt-3">

                    Gunakan dashboard ini untuk mengelola repository,
                    membuat perangkat pembelajaran, dan memantau
                    perkembangan administrasi guru.

                </p>



                <a href="{{ route('guru.repository') }}"
                   class="btn btn-info mr-2">

                    <i class="fas fa-images mr-2"></i>
                    Repository

                </a>



                <a href="{{ route('guru.perangkat.index') }}"
                   class="btn btn-primary">

                    <i class="fas fa-book mr-2"></i>
                    Perangkat Pembelajaran

                </a>


            </div>



            <div class="col-md-4 text-center">


                <i class="fas fa-chalkboard-teacher text-primary"
                   style="font-size:120px;opacity:.2">
                </i>


            </div>


        </div>


    </div>


</div>




{{-- Informasi Sistem --}}

<div class="row mt-4">


    <div class="col-md-6">


        <div class="card shadow-sm border-0">


            <div class="card-header bg-white">

                <h5 class="font-weight-bold mb-0">

                    <i class="fas fa-info-circle text-primary mr-2"></i>
                    Informasi Sistem

                </h5>

            </div>


            <div class="card-body">


                <p class="mb-2">

                    <i class="fas fa-check text-success mr-2"></i>

                    Kelola dokumen pembelajaran secara digital

                </p>


                <p class="mb-2">

                    <i class="fas fa-check text-success mr-2"></i>

                    Simpan repository kegiatan guru

                </p>


                <p class="mb-0">

                    <i class="fas fa-check text-success mr-2"></i>

                    Pantau status perangkat pembelajaran

                </p>


            </div>


        </div>


    </div>



    <div class="col-md-6">


        <div class="card shadow-sm border-0">


            <div class="card-header bg-white">

                <h5 class="font-weight-bold mb-0">

                    <i class="fas fa-clock text-warning mr-2"></i>
                    Aktivitas Guru

                </h5>


            </div>


            <div class="card-body">


                <p class="text-muted mb-0">

                    Dashboard akan menampilkan perkembangan
                    aktivitas dan administrasi pembelajaran guru.

                </p>


            </div>


        </div>


    </div>



</div>



@stop