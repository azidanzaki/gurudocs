@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-chart-line mr-2"></i>Dashboard Admin</h1>
@stop

@section('content')

<div class="row">
    {{-- Total Guru --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalGuru }}</h3>
                <p>Total Guru</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users') }}" class="small-box-footer">
                Kelola User <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Total Mapel --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalMapel }}</h3>
                <p>Total Mata Pelajaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('admin.kelolaperangkat') }}" class="small-box-footer">
                Kelola Mapel <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Total Kelas --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalKelas }}</h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <a href="{{ route('admin.kelolaperangkat') }}" class="small-box-footer text-dark">
                Kelola Kelas <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Total Dokumen Administratif --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalDokumen }}</h3>
                <p>Dokumen Administratif</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <a href="{{ route('admin.dokumenadm.index') }}" class="small-box-footer">
                Kelola Dokumen <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h3 class="card-title font-weight-bold text-secondary">
                    <i class="fas fa-user-plus mr-2"></i> Guru Terbaru
                </h3>
            </div>
            <div class="card-body">
                @if($recentGurus->isEmpty())
                    <p class="text-muted text-center py-4">Belum ada data guru.</p>
                @else
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($recentGurus as $guru)
                            <li class="item d-flex align-items-center py-3 border-bottom">
                                <div class="product-img mr-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold" style="width:40px; height:40px;">
                                        {{ substr($guru->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="product-info flex-grow-1">
                                    <a href="javascript:void(0)" class="product-title text-dark font-weight-bold">
                                        {{ $guru->name }}
                                        <span class="badge badge-info float-right">{{ $guru->created_at->diffForHumans() }}</span>
                                    </a>
                                    <span class="product-description text-muted small">
                                        NIP: {{ $guru->nip ?? '-' }} | Email: {{ $guru->email ?? '-' }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 bg-primary text-white h-100">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                <i class="fas fa-cogs fa-4x mb-4 text-white-50"></i>
                <h3 class="font-weight-bold">Sistem Manajemen Perangkat</h3>
                <p class="mt-2 mb-0 text-light">Kelola pengguna, struktur kelas, dan dokumen administrasi untuk memastikan kelancaran kegiatan belajar mengajar.</p>
            </div>
        </div>
    </div>
</div>

@stop