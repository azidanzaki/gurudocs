@extends('adminlte::page')

@section('title', 'Penilaian Kinerja Guru - ' . $guru->name)

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
    <h1 class="font-weight-bold text-dark m-0">Penilaian Kinerja Guru</h1>
    <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-outline-secondary btn-sm shadow-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Profil
    </a>
</div>
@stop

@section('content')
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden; margin-bottom: 24px;">
    <div class="card-header bg-white border-bottom-0 p-4 d-flex align-items-center flex-wrap gap-3">
        <div class="flex-grow-1">
            <h4 class="mb-0 font-weight-bold text-dark" style="font-size: 1.3rem;">
                <i class="fas fa-star-half-alt mr-2 text-primary"></i>
                Evaluasi Kinerja: {{ $mapel ? $mapel->nama_mapel : 'Semua Mapel' }}
            </h4>
            <div class="d-flex align-items-center mt-2 flex-wrap gap-2 text-muted" style="font-size: 0.9rem;">
                <span class="mr-3"><i class="fas fa-chalkboard mr-1"></i> Kelas {{ $kelas ? $kelas->nama_kelas : 'Semua Kelas' }}</span>
                <span class="mr-3"><i class="fas fa-calendar-alt mr-1"></i> TA {{ $selectedTahunObj ? $selectedTahunObj->nama : '' }}</span>
                <span><i class="fas fa-user-tie mr-1"></i> Guru: <strong>{{ $guru->name }}</strong></span>
            </div>
        </div>

        @php
            $allSubmitted = true;
            for ($i = 1; $i <= 7; $i++) {
                if (!isset($penilaians[$i]) || $penilaians[$i]->status !== 'submitted') {
                    $allSubmitted = false;
                    break;
                }
            }
        @endphp
        @if($allSubmitted)
        <a href="{{ route('kepala.penilaian.cetakPkg', ['id' => $guru->id, 'mapel_id' => $mapel ? $mapel->id : null, 'kelas_id' => $kelas ? $kelas->id : null, 'tahun_ajaran_id' => $selectedTahunId]) }}"
           class="btn btn-success shadow-sm ml-auto d-flex align-items-center"
           style="border-radius: 8px; font-weight: bold;" target="_blank">
            <i class="fas fa-print mr-2"></i> Cetak Hasil Penilaian
        </a>
        @endif
    </div>
    
    <div class="card-body p-4 bg-light">
        <div class="list-group">
            @php
                $aspects = [
                    1 => 'Perencanaan Pembelajaran',
                    2 => 'Pelaksanaan Pembelajaran',
                    3 => 'Membuka dan Menutup Pembelajaran',
                    4 => 'Pelaksanaan Variasi Stimulus Pembelajaran',
                    5 => 'Pelaksanaan Keterampilan Bertanya',
                    6 => 'Pelaksanaan Memberikan Penguatan',
                    7 => 'Pelaksanaan Menguatkan Kesimpulan Peserta Didik',
                ];
            @endphp
            @foreach($aspects as $i => $title)
                @php
                    $draft = isset($penilaians) && isset($penilaians[$i]) ? $penilaians[$i] : null;
                    $btnText = 'Mulai Penilaian';
                    $btnClass = 'btn-outline-primary';
                    $icon = 'fa-chevron-right';
                    $statusBadge = '<span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 4px; font-weight: 500; font-size: 0.8rem;"><i class="fas fa-clock mr-1"></i> Belum Mulai</span>';
                    
                    if ($draft && $draft->status === 'submitted') {
                        $btnText = 'Lihat';
                        $btnClass = 'btn-success';
                        $icon = 'fa-check-circle';
                        $statusBadge = '<span class="badge badge-success px-2 py-1" style="border-radius: 4px; font-weight: 500; font-size: 0.8rem;"><i class="fas fa-check-circle mr-1"></i> Selesai</span>';
                    } elseif ($draft && $draft->status === 'draft') {
                        $btnText = 'Lanjutkan';
                        $btnClass = 'btn-warning text-dark';
                        $icon = 'fa-edit';
                        $statusBadge = '<span class="badge badge-warning text-dark px-2 py-1" style="border-radius: 4px; font-weight: 500; font-size: 0.8rem;"><i class="fas fa-edit mr-1"></i> Draft</span>';
                    }
                @endphp
                <div class="list-group-item d-flex justify-content-between align-items-center p-3 aspect-item mb-2 bg-white border shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="text-white font-weight-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" 
                             style="width: 42px; height: 42px; font-size: 1.1rem; background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                            {{ $i }}
                        </div>
                        <div class="ml-3">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                <h5 class="font-weight-bold mb-0 text-dark" style="font-size: 1.05rem;">{{ $title }}</h5>
                                {!! $statusBadge !!}
                            </div>
                            <span class="text-muted small">Formulir penilaian untuk aspek {{ strtolower($title) }}.</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('kepala.penilaian.start', [$guru->id, $i, 'mapel_id' => $mapel ? $mapel->id : '', 'kelas_id' => $kelas ? $kelas->id : '', 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn {{ $btnClass }} btn-sm font-weight-bold px-3 d-flex align-items-center" style="border-radius: 6px; height: 35px;">
                            <span>{{ $btnText }}</span>
                            <i class="fas {{ $icon }} ml-2" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .aspect-item {
        transition: all 0.25s ease;
        border-radius: 12px !important;
    }
    .aspect-item:hover {
        background-color: #fbfcfe !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06) !important;
        border-color: #4f46e5 !important;
    }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
</style>
@stop
