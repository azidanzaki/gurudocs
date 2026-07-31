@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-chart-line mr-2"></i>Dashboard Admin</h1>
@stop

@section('content')


<style>

.dashboard-card {
    border-radius: 15px;
    overflow: hidden;
    transition: .3s;
    border:none;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,.15);
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



.bg-gradient-danger-custom {
    background: linear-gradient(135deg,#dc3545,#ff6b6b);
}


.welcome-card {
    border-radius:15px;
}

</style>




<div class="row">


    {{-- Kelola User --}}
    <div class="col-lg-4 col-md-6">


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

                <i class="fas fa-users"></i>

            </div>




            <a href="{{ route('admin.users') }}"
               class="small-box-footer">


                Kelola User

                <i class="fas fa-arrow-right ml-2"></i>


            </a>



        </div>


    </div>





    {{-- Kelola Perangkat --}}
    <div class="col-lg-4 col-md-6">


        <div class="small-box dashboard-card bg-gradient-success-custom text-white shadow">


            <div class="inner p-4">


                <h2 class="font-weight-bold">

                    <i class="fas fa-book"></i>

                </h2>


                <p class="mb-0">

                    Kelola Perangkat

                </p>


            </div>




            <div class="icon-dashboard">

                <i class="fas fa-cogs"></i>

            </div>




            <a href="{{ route('admin.kelolaperangkat') }}"
               class="small-box-footer">


                Kelola Perangkat

                <i class="fas fa-arrow-right ml-2"></i>


            </a>



        </div>


    </div>







    {{-- Template Dokumen --}}
    <div class="col-lg-4 col-md-6">


        <div class="small-box dashboard-card bg-gradient-danger-custom text-white shadow">


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




            <a href="{{ route('admin.dokumenadm.index') }}"
               class="small-box-footer">


                Kelola Template Dokumen

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


                    Dashboard admin digunakan untuk mengelola
                    pengguna, perangkat pembelajaran, serta
                    template dokumen administrasi guru.


                </p>





                <a href="{{ route('admin.users') }}"
                   class="btn btn-info mr-2">


                    <i class="fas fa-users mr-2"></i>

                    Kelola User


                </a>




                <a href="{{ route('admin.kelolaperangkat') }}"
                   class="btn btn-success mr-2">


                    <i class="fas fa-cogs mr-2"></i>

                    Kelola Perangkat


                </a>




                <a href="{{ route('admin.dokumenadm.index') }}"
                   class="btn btn-danger">


                    <i class="fas fa-file-alt mr-2"></i>

                    Template Dokumen


                </a>



            </div>





            <div class="col-md-4 text-center">


                <i class="fas fa-user-shield text-primary"
                   style="font-size:120px;opacity:.2">

                </i>


            </div>



        </div>


    </div>


</div>



@stop