@extends('adminlte::page')

@section('title', 'Dashboard Kepala Sekolah')


@section('content_header')

<div>

    <h1 class="font-weight-bold text-dark">
        <i class="fas fa-university mr-2 text-primary"></i>
        Dashboard Kepala Sekolah
    </h1>

    <p class="text-muted mb-0">
        Ringkasan penilaian kinerja guru dan administrasi pembelajaran
    </p>

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


    {{-- Total Guru --}}
    <div class="col-lg-6 col-md-6">

        <div class="small-box dashboard-card bg-gradient-info-custom text-white shadow">

            <div class="inner p-4">

                <h2 class="font-weight-bold">
                    {{ $totalGuru }}
                </h2>

                <p class="mb-0">
                    Total Guru
                </p>

            </div>


            <div class="icon-dashboard">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>


            <a href="{{ route('kepala.penilaian') }}"
               class="small-box-footer">

                Mulai Penilaian Guru

                <i class="fas fa-arrow-right ml-2"></i>

            </a>


        </div>

    </div>





    {{-- Template Dokumen --}}
    <div class="col-lg-6 col-md-6">


        <div class="small-box dashboard-card bg-gradient-success-custom text-white shadow">


            <div class="inner p-4">


                <h2 class="font-weight-bold">

                    <i class="fas fa-folder-open"></i>

                </h2>


                <p class="mb-0">

                    Template Dokumen

                </p>


            </div>



            <div class="icon-dashboard">

                <i class="fas fa-file-alt"></i>

            </div>



            <a href="{{ route('guru.dokumenadmguru.index') }}"
               class="small-box-footer">


                Lihat Template


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


                    Dashboard ini digunakan untuk melakukan
                    penilaian kinerja guru, memantau kelengkapan
                    dokumen pembelajaran, dan mengelola evaluasi
                    guru.


                </p>





                <a href="{{ route('kepala.penilaian') }}"
                   class="btn btn-info mr-2">


                    <i class="fas fa-clipboard-check mr-2"></i>

                    Penilaian Guru


                </a>





                <a href="{{ route('guru.dokumenadmguru.index') }}"
                   class="btn btn-success">


                    <i class="fas fa-folder-open mr-2"></i>

                    Template Dokumen


                </a>



            </div>





            <div class="col-md-4 text-center">


                <i class="fas fa-user-tie text-primary"
                   style="font-size:120px;opacity:.2">

                </i>


            </div>


        </div>


    </div>


</div>



@stop