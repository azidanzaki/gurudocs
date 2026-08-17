@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Dashboard Admin</h1>
@stop

@section('content')

<style>
    .welcome-banner {
        background: linear-gradient(135deg, #007bff, #6610f2);
        border-radius: 16px;
        color: white;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
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
    
    .bg-light-primary { background-color: #e8f4fd; color: #007bff; }
    .bg-light-success { background-color: #e6f7ec; color: #28a745; }
    .bg-light-danger { background-color: #fbeded; color: #dc3545; }
    
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
            Dashboard admin digunakan untuk mengelola pengguna, perangkat pembelajaran, serta dokumen-dokumen yang dibutuhkan guru dengan cepat dan efisien.
        </p>
        <div class="d-flex gap-2 flex-wrap" style="gap: 10px; display: flex;">
            <a href="{{ route('admin.users') }}" class="btn font-weight-bold mr-2 border-0 px-4 shadow-sm" style="background: white; color: #007bff; border-radius: 50px;">
                <i class="fas fa-users mr-1"></i> Kelola Pengguna
            </a>
            <a href="{{ route('admin.kelolaperangkat') }}" class="btn font-weight-bold border-2 px-4" style="border: 2px solid white; color: white; border-radius: 50px;">
                <i class="fas fa-cogs mr-1"></i> Kelola Perangkat
            </a>
        </div>
    </div>
    <i class="fas fa-user-shield" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%); font-size: 140px; opacity: 0.15; z-index: 1;"></i>
</div>

{{-- Stat Cards --}}
<div class="row">
    {{-- Total Guru --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('admin.users') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ $totalGuru }}</h3>
                    <p>Total Guru Aktif</p>
                </div>
                <div class="action-link text-primary">
                    Kelola User <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Kelola Perangkat --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('admin.kelolaperangkat') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-success">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ \App\Models\Mapel::count() }}</h3>
                    <p>Mata Pelajaran</p>
                </div>
                <div class="action-link text-success">
                    Kelola Perangkat <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Template Dokumen --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route('admin.dokumenadm.index') }}" class="text-decoration-none">
            <div class="modern-stat-card">
                <div class="icon-wrapper bg-light-danger">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="info-wrapper pb-3">
                    <h3>{{ \App\Models\DokumenAdm::count() }}</h3>
                    <p>Dokumen</p>
                </div>
                <div class="action-link text-danger">
                    Kelola Dokumen <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>
</div>

@stop
