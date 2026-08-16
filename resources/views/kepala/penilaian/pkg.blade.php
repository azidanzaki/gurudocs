@extends('adminlte::page')

@section('title', 'Penilaian Kinerja Guru - ' . $guru->name)

@section('content_header')
    <h1>Penilaian Kinerja Guru - {{ $guru->name }}</h1>
@stop

@section('content')
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
<div class="mb-3">
    <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Profil
    </a>
</div>

<div class="card shadow-sm border-0" style="border-radius: 10px;">
<div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
    <div>
        <h4 class="mb-0 font-weight-bold text-primary">
            <i class="fas fa-star-half-alt mr-2"></i>
            Penilaian Kinerja Guru - {{ $mapel ? $mapel->nama_mapel : 'Semua Mapel' }} ({{ $kelas ? $kelas->nama_kelas : 'Semua Kelas' }}) - Tahun Ajaran {{ $selectedTahunObj ? $selectedTahunObj->nama : '' }}
        </h4>
        <p class="text-muted mb-0 mt-1">
            Pilih aspek di bawah ini untuk mulai mengisi formulir penilaian kinerja untuk guru mata pelajaran {{ $mapel ? $mapel->nama_mapel : '' }} kelas {{ $kelas ? $kelas->nama_kelas : '' }}.
        </p>
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
       class="btn btn-primary ml-auto"
       style="border-radius: 6px;" target="_blank">
        <i class="fas fa-print mr-1"></i> Cetak Hasil Penilaian
    </a>
    @endif
</div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
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
                <li class="list-group-item d-flex justify-content-between align-items-center p-4 aspect-item" style="border-left: 4px solid transparent;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white font-weight-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ $i }}
                        </div>
                        <div class="ml-4">
                            <h5 class="font-weight-bold mb-1" style="font-size: 1.15rem;">{{ $title }}</h5>
                            <span class="text-muted">Formulir penilaian kinerja guru untuk {{ $title }}</span>
                        </div>
                    </div>
                    <div>
                        @php
                            $draft = isset($penilaians) && isset($penilaians[$i]) ? $penilaians[$i] : null;
                            $btnText = 'Mulai Penilaian';
                            $btnClass = 'btn-outline-primary';
                            $icon = 'fa-clipboard-list';
                            
                            if ($draft && $draft->status === 'submitted') {
                                $btnText = 'Lihat Penilaian';
                                $btnClass = 'btn-success';
                                $icon = 'fa-check-circle';
                            } elseif ($draft && $draft->status === 'draft') {
                                $btnText = 'Lanjutkan Penilaian';
                                $btnClass = 'btn-warning text-dark';
                                $icon = 'fa-edit';
                            }
                        @endphp
                        <a href="{{ route('kepala.penilaian.start', [$guru->id, $i, 'mapel_id' => $mapel ? $mapel->id : '', 'kelas_id' => $kelas ? $kelas->id : '', 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn {{ $btnClass }} font-weight-bold px-4" style="border-radius: 6px;">
                            <i class="fas {{ $icon }} mr-1"></i> {{ $btnText }}
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@stop

@section('css')
<style>
    .aspect-item {
        transition: all 0.2s ease-in-out;
    }
    .aspect-item:hover {
        background-color: #f8fcfd;
        border-left: 4px solid #007bff !important;
        box-shadow: inset 0 0 10px rgba(0,123,255,0.05);
    }
</style>
@stop
