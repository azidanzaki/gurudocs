@extends('adminlte::page')

@section('title', 'Dashboard Kepala Sekolah')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-university mr-2"></i>Dashboard Kepala Sekolah</h1>
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
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <a href="{{ route('kepala.penilaian') }}" class="small-box-footer">
                Lihat Daftar Guru <i class="fas fa-arrow-circle-right"></i>
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
                <i class="fas fa-book-open"></i>
            </div>
            <a href="#" class="small-box-footer text-success">
                . <i class="fas fa-arrow-circle-right" style="opacity: 0;"></i>
            </a>
        </div>
    </div>

    {{-- Dokumen Selesai --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalSelesai }}</h3>
                <p>Dokumen Telah Disubmit</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <a href="#" class="small-box-footer text-primary">
                . <i class="fas fa-arrow-circle-right" style="opacity: 0;"></i>
            </a>
        </div>
    </div>

    {{-- Dokumen Draft --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalDraft }}</h3>
                <p>Dokumen Masih Draft</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="#" class="small-box-footer text-warning">
                . <i class="fas fa-arrow-circle-right" style="opacity: 0;"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h3 class="card-title font-weight-bold text-secondary">
                    <i class="fas fa-history mr-2"></i> Aktivitas Penyelesaian Perangkat Terbaru
                </h3>
            </div>
            <div class="card-body">
                @if($recentSubmissions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada perangkat yang diselesaikan oleh guru.</h5>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Guru</th>
                                    <th>Dokumen Perangkat</th>
                                    <th>Mapel & Kelas</th>
                                    <th>Disubmit Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions as $submission)
                                    <tr>
                                        <td class="font-weight-bold text-dark">
                                            <i class="fas fa-user-circle text-primary mr-1"></i> {{ $submission->user->name }}
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> {{ $submission->template->nama_perangkat }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $submission->mapel->nama_mapel }}</span>
                                            <span class="badge badge-secondary">{{ $submission->kelas->nama_kelas }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $submission->updated_at->format('d M Y, H:i') }}
                                            <br>
                                            <span class="text-xs">({{ $submission->updated_at->diffForHumans() }})</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@stop