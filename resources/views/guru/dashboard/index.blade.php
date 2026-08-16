@extends('adminlte::page')

@section('title', 'Dashboard Guru')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold text-dark">
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
    .welcome-banner {
        background: linear-gradient(135deg, #17a2b8, #0056b3);
        border-radius: 16px;
        color: white;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(23, 162, 184, 0.3);
        margin-bottom: 30px;
    }
    
    .welcome-banner::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: "";
        position: absolute;
        bottom: -80px;
        right: 100px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
    }
    
    .welcome-banner .title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .welcome-banner .subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 20px;
        max-width: 600px;
    }
    
    .modern-stat-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        background: #fff;
        display: flex;
        align-items: center;
        padding: 24px;
        position: relative;
        z-index: 1;
    }
    
    .modern-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }
    
    .modern-stat-card .icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-right: 20px;
        flex-shrink: 0;
    }
    
    .modern-stat-card .info-wrapper h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
        line-height: 1;
    }
    
    .modern-stat-card .info-wrapper p {
        margin: 5px 0 0 0;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .bg-light-info { background-color: #e3f2fd; color: #17a2b8; }
    .bg-light-success { background-color: #e6f7ec; color: #28a745; }
    .bg-light-warning { background-color: #fff3cd; color: #ffc107; }
    
    .action-link {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.03);
        text-align: center;
        padding: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.2s ease;
        color: inherit;
    }
    
    .modern-stat-card:hover .action-link {
        background: rgba(0,0,0,0.06);
    }
</style>

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div style="position: relative; z-index: 2;">
        <h2 class="title">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
        <p class="subtitle">
            Gunakan dashboard ini untuk mengelola repository, membuat perangkat pembelajaran, dan memantau perkembangan administrasi Anda dengan mudah.
        </p>
        <div class="d-flex gap-2 flex-wrap" style="gap: 10px; display: flex;">
            <a href="{{ route('guru.perangkat.index') }}" class="btn font-weight-bold mr-2 border-0 px-4 shadow-sm" style="background: white; color: #17a2b8; border-radius: 50px;">
                <i class="fas fa-book mr-1"></i> Isi Perangkat
            </a>
            <a href="{{ route('guru.repository') }}" class="btn font-weight-bold border-2 px-4" style="border: 2px solid white; color: white; border-radius: 50px;">
                <i class="fas fa-images mr-1"></i> Buka Repository
            </a>
        </div>
    </div>
    <i class="fas fa-chalkboard-teacher" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%); font-size: 140px; opacity: 0.15; z-index: 1;"></i>
</div>

<div class="row">

    {{-- Repository --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('guru.repository') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-info">
                    <i class="fas fa-images"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ $totalRepository }}</h3>
                    <p>Kegiatan Repository</p>
                </div>
                <div class="action-link text-info">
                    Kelola Repository <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Selesai --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('guru.perangkat.history') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ $totalSelesai }}</h3>
                    <p>Perangkat Selesai</p>
                </div>
                <div class="action-link text-success">
                    Lihat History <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Draft --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('guru.perangkat.index') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-warning">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ $totalDraft }}</h3>
                    <p>Perangkat Tertunda</p>
                </div>
                <div class="action-link text-warning">
                    Lanjutkan Pengerjaan <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

</div>

{{-- Informasi Sistem --}}
<div class="row mt-2">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-info-circle text-info mr-2"></i>
                    Informasi Sistem
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="line-height: 2.2;">
                    <li><i class="fas fa-check-circle text-success mr-2"></i> Kelola dokumen pembelajaran secara digital</li>
                    <li><i class="fas fa-check-circle text-success mr-2"></i> Simpan repository kegiatan guru</li>
                    <li><i class="fas fa-check-circle text-success mr-2"></i> Pantau status perangkat pembelajaran</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    Aktivitas Guru
                </h5>
            </div>
            <div class="card-body d-flex align-items-center">
                <p class="text-muted mb-0" style="font-size: 1.05rem;">
                    Dashboard akan menampilkan perkembangan aktivitas dan administrasi pembelajaran secara real-time. Pastikan Anda menyelesaikan pengisian sebelum batas tenggat waktu.
                </p>
            </div>
        </div>
    </div>
</div>

@stop
