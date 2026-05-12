@extends('adminlte::page')

@section('title', 'Profil Guru')

@section('content_header')
    <h1>Profil</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <div class="row align-items-center">

            <!-- FOTO -->
            <div class="col-md-4 text-center">

                <img src="{{ asset('images/logomts.png') }}"
                     class="img-circle elevation-2 mb-3"
                     style="width:160px; height:160px; object-fit:cover;">

                <h4 class="mb-1">Zakiah</h4>
                <p class="text-muted mb-1">1234567890987</p>
                <p class="text-muted">Quran Hadits IX, SKI VII</p>

            </div>

            <!-- INFO -->
            <div class="col-md-8">

                <h5>About</h5>

                <p>
                    Guru Quran Hadits di MTsN 3 Rokan Hulu, Wali kelas IX 8.
                    Mulai mengajar sejak 2020, sebelumnya di MIN Pasir Pengaraian.
                </p>

                <hr>

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-success me-2"></i>
                            081234567890
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-success me-2"></i>
                            contoh@gmail.com
                        </div>
                    </div>

                </div>

                <a href="#" class="btn btn-success mt-3">
                    <i class="fas fa-pen-to-square me-1"></i>
                    Edit Profile
                </a>

            </div>

        </div>

    </div>

</div>

@stop