@extends('adminlte::page')

@section('title', 'Detail Perangkat')

@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <div>
        <h1>{{ $mapel->nama_mapel }}</h1>

        <p class="text-muted mb-0">
            Perangkat Pembelajaran
        </p>
    </div>

</div>

@stop

@section('content')

<div class="row">

@foreach($mapel->perangkats as $item)

<div class="col-md-4 mb-4">

    <div class="card border-0 shadow-sm h-100">

        <div class="card-body">

            <div class="d-flex align-items-center mb-3">

                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px; height:55px;">

                    <i class="fas fa-file-word text-white"></i>

                </div>

                <div class="ml-3">

                    <h5 class="font-weight-bold mb-0">
                        {{ $item->nama_perangkat }}
                    </h5>

                    <small class="text-muted">
                        {{ $item->tahun_ajaran }}
                    </small>

                </div>

            </div>

            <div class="mb-3">

                <strong>
                    {{ $item->judul }}
                </strong>

            </div>

            <button class="btn btn-outline-success btn-block">

                Buka Dokumen

            </button>

        </div>

    </div>

</div>

@endforeach

</div>

@stop