@extends('adminlte::page')

@section('title', 'Perangkat Pembelajaran')

@section('content_header')
<h1>Perangkat Pembelajaran</h1>
@stop

@section('content')

<div class="row">

@foreach($mapels as $mapel)

<div class="col-md-4">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex align-items-center mb-3">

                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px; height:60px;">

                    <i class="fas fa-book text-white"></i>

                </div>

                <div class="ml-3">

                    <h5 class="mb-0 font-weight-bold">
                        {{ $mapel->nama_mapel }}
                    </h5>

                    <small class="text-muted">
                        Mata Pelajaran
                    </small>

                </div>

            </div>

            <a href="{{ route('guru.perangkat.show', $mapel->id) }}"
               class="btn btn-success btn-block">

                Siapkan Perangkat

            </a>

        </div>

    </div>

</div>

@endforeach

</div>

@stop