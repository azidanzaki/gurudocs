@extends('adminlte::page')

@section('title', 'siapkan perangkat — ' . $mapel->nama_mapel)

@section('content_header')
    <h1>siapkan perangkat &mdash; {{ $mapel->nama_mapel }}</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('guru.perangkat.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">

    @forelse($kelas as $k)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">

                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; flex-shrink:0;">
                        <i class="fas fa-chalkboard text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">{{ $k->nama_kelas_simple }}</h5>
                        <small class="text-muted">Kelas</small>
                    </div>
                </div>

                <a href="{{ route('guru.perangkat.kelas', [$mapel->id, $k->id]) }}"
                   class="btn btn-primary btn-block mt-auto">
                    Lengkapi Perangkat
                </a>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                <h5>Anda belum memiliki kelas untuk mata pelajaran ini.</h5>
                <p class="text-muted">Minta admin untuk mengatur kelas yang Anda ampu.</p>
            </div>
        </div>
    </div>
    @endforelse

</div>

@stop