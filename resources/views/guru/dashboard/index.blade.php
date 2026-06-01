@extends('adminlte::page')

@section('title', 'Dashboard Guru')

@section('content_header')
    <h1 class="font-weight-bold text-dark"><i class="fas fa-home mr-2"></i>Dashboard Utama</h1>
@stop

@section('content')

<div class="row">
    {{-- Total Kelas --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalKelas }}</h3>
                <p>Kelas yang Diajar</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <a href="#" class="small-box-footer">
                Info detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    {{-- Total Mapel --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalMapel }}</h3>
                <p>Mata Pelajaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('guru.perangkat.index') }}" class="small-box-footer">
                Lihat Perangkat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Perangkat Selesai --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalSelesai }}</h3>
                <p>Perangkat Selesai</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('guru.perangkat.history') }}" class="small-box-footer">
                Lihat History <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Perangkat Draft --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm rounded">
            <div class="inner">
                <h3>{{ $totalDraft }}</h3>
                <p>Perangkat Tertunda (Draft)</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
            <a href="#draft-section" class="small-box-footer text-dark">
                Lanjutkan <i class="fas fa-arrow-circle-down"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7" id="draft-section">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h3 class="card-title font-weight-bold text-danger">
                    <i class="fas fa-tasks mr-2"></i> Tugas Perangkat Tertunda
                </h3>
            </div>
            <div class="card-body">
                @if($draftTerbaru->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
                        <h5>Hebat!</h5>
                        <p class="text-muted">Anda tidak memiliki perangkat berstatus draft saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Dokumen</th>
                                    <th>Mapel/Kelas</th>
                                    <th>Terakhir Diubah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($draftTerbaru as $draft)
                                    <tr>
                                        <td class="font-weight-bold text-primary">{{ $draft->template->nama_perangkat }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $draft->mapel->nama_mapel }}</span>
                                            <span class="badge badge-secondary">{{ $draft->kelas->nama_kelas }}</span>
                                            @if($draft->bab > 0)
                                                <span class="badge badge-warning">Bab {{ $draft->bab }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $draft->updated_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('guru.perangkat.edit', [$draft->mapel_id, $draft->kelas_id, $draft->perangkat_template_id]) }}?semester={{ $draft->semester }}&bab={{ $draft->bab }}" class="btn btn-sm btn-outline-primary">
                                                Lanjutkan <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
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

    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h3 class="card-title font-weight-bold text-secondary">
                    <i class="fas fa-bullhorn mr-2"></i> Informasi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 shadow-none mb-3">
                    <h5 class="font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Selamat Datang, {{ Auth::user()->name }}!</h5>
                    Jangan lupa melengkapi seluruh perangkat pembelajaran sesuai dengan pembagian yang ada:
                    <ul class="mb-0 mt-2">
                        <li><strong>Tahunan:</strong> Dibuat 1 kali di awal tahun</li>
                        <li><strong>Semesteran:</strong> Dibuat di tiap semester</li>
                        <li><strong>Per Bab:</strong> Modul ajar setiap bab/materi pokok</li>
                    </ul>
                </div>
                
                <a href="{{ route('guru.perangkat.index') }}" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm">
                    <i class="fas fa-file-signature mr-2"></i> Buka Menu Perangkat Pembelajaran
                </a>
            </div>
        </div>
    </div>
</div>

@stop