@extends('adminlte::page')

@section('title', 'Dashboard Kepala Sekolah')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold text-dark">
                Dashboard Kepala Sekolah
            </h1>
            <p class="text-muted mb-0">
                Pemantauan penilaian kinerja guru dan dokumen administrasi
            </p>
        </div>
    </div>
@stop

@section('content')

<style>
    .welcome-banner {
        background: linear-gradient(135deg, #6f42c1, #007bff);
        border-radius: 16px;
        color: white;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(111, 66, 193, 0.3);
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
        height: 100%;
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
    .bg-light-warning { background-color: #fff3cd; color: #ffc107; }
    .bg-light-info { background-color: #e3f2fd; color: #17a2b8; }
    
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
            Dashboard ini merangkum seluruh aktivitas guru, persentase pengisian perangkat pembelajaran, serta penilaian kinerja guru.
        </p>
        <div class="d-flex gap-2 flex-wrap" style="gap: 10px; display: flex;">
            <a href="{{ route('kepala.penilaian') }}" class="btn font-weight-bold mr-2 border-0 px-4 shadow-sm" style="background: white; color: #6f42c1; border-radius: 50px;">
                <i class="fas fa-clipboard-check mr-1"></i> Penilaian Guru
            </a>
            <a href="{{ route('guru.dokumenadmguru.index') }}" class="btn font-weight-bold border-2 px-4" style="border: 2px solid white; color: white; border-radius: 50px;">
                <i class="fas fa-folder-open mr-1"></i> Template Dokumen
            </a>
        </div>
    </div>
    <i class="fas fa-user-tie" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%); font-size: 140px; opacity: 0.15; z-index: 1;"></i>
</div>

{{-- Stat Cards --}}
<div class="row">
    {{-- Total Guru --}}
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('kepala.penilaian') }}" class="text-decoration-none">
            <div class="modern-stat-card pb-4">
                <div class="icon-wrapper bg-light-primary">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="info-wrapper">
                    <h3>{{ $totalGuru }}</h3>
                    <p>Total Guru</p>
                </div>
                <div class="action-link text-primary">
                    Mulai Penilaian <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Perangkat Selesai --}}
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-stat-card pb-4">
            <div class="icon-wrapper bg-light-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="info-wrapper">
                <h3>{{ $totalSelesai }}</h3>
                <p>Perangkat Selesai</p>
            </div>
        </div>
    </div>

    {{-- Perangkat Draft --}}
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-stat-card pb-4">
            <div class="icon-wrapper bg-light-warning">
                <i class="fas fa-edit"></i>
            </div>
            <div class="info-wrapper">
                <h3>{{ $totalDraft }}</h3>
                <p>Perangkat Draft</p>
            </div>
        </div>
    </div>

    {{-- Template Dokumen --}}
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('guru.dokumenadmguru.index') }}" class="text-decoration-none">
            <div class="modern-stat-card pb-4">
                <div class="icon-wrapper bg-light-info">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="info-wrapper">
                    <h3>{{ $totalMapel }}</h3>
                    <p>Mata Pelajaran</p>
                </div>
                <div class="action-link text-info">
                    Lihat Dokumen <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Recent Submissions --}}
<div class="card shadow-sm border-0 mt-2" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 pt-4 pb-2">
        <h3 class="card-title font-weight-bold"><i class="fas fa-history text-muted mr-2"></i>Aktivitas Pengumpulan Terbaru</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3">Nama Guru</th>
                        <th class="border-0 py-3">Perangkat</th>
                        <th class="border-0 py-3">Kelas & Mapel</th>
                        <th class="border-0 py-3 text-center">Waktu Pengumpulan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSubmissions as $sub)
                        <tr>
                            <td class="px-4 py-3 font-weight-bold text-dark">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->user->name) }}&background=random&color=fff" class="img-circle mr-3" style="width: 32px; height: 32px;" alt="Avatar">
                                    {{ $sub->user->name }}
                                </div>
                            </td>
                            <td class="py-3 align-middle">{{ $sub->template->nama_perangkat }}</td>
                            <td class="py-3 align-middle">
                                <span class="badge badge-light border text-dark">{{ $sub->kelas->nama_kelas }}</span>
                                <span class="badge badge-info">{{ $sub->mapel->nama_mapel }}</span>
                            </td>
                            <td class="py-3 align-middle text-center">
                                <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $sub->updated_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">Belum ada perangkat yang dikumpulkan akhir-akhir ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
