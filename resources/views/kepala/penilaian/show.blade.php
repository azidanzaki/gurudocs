@extends('adminlte::page')

@section('title', 'Profil Guru - ' . $guru->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-3">
        <h1 class="m-0 text-dark font-weight-bold">Profil Guru</h1>
        <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
            <label for="tahun_ajaran_id" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
            <form action="{{ route('kepala.penilaian.show', $guru->id) }}" method="GET" class="mb-0">
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control border-0 bg-light mb-0" style="border-radius: 8px; font-weight: bold; min-width: 190px; width: auto;" onchange="this.form.submit()">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTahunId == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@stop

@section('content')
<div class="mb-4">
    <a href="{{ route('kepala.penilaian') }}" class="btn btn-light border px-3 shadow-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body box-profile p-4">
                <div class="text-center">
                    <img src="{{ $guru->foto ? asset('storage/' . $guru->foto) : asset('images/logomts.png') }}" class="img-circle elevation-2 mb-3 shadow-sm"
                         style="width:140px; height:140px; object-fit:cover; border: 4px solid #fff;">
                </div>

                <h3 class="profile-username text-center">
                    {{ $guru->name }}
                </h3>

                <p class="text-muted text-center">
                    Guru<br><small>NIP: {{ $guru->nip }}</small>
                </p>

                <ul class="list-group list-group-unbordered mb-3 mt-4">
                    <li class="list-group-item border-top-0 border-left-0 border-right-0 px-0">
                        <b class="text-muted"><i class="fas fa-envelope mr-1"></i> Email</b> <a class="float-right text-dark font-weight-bold">{{ $guru->email ?? '-' }}</a>
                    </li>
                    <li class="list-group-item border-left-0 border-right-0 px-0">
                        <b class="text-muted"><i class="fas fa-phone mr-1"></i> No. HP</b> <a class="float-right text-dark font-weight-bold">{{ $guru->no_hp ?? '-' }}</a>
                    </li>
                </ul>

                <h6 class="font-weight-bold mt-4 mb-3"><i class="fas fa-chalkboard-teacher mr-1 text-primary"></i> Mengajar:</h6>
                <ul class="list-group list-group-unbordered mb-3">
                    @php
                        $mapelsGrouped = [];
                        foreach ($mengajarAssignments as $m) {
                            $m_obj = $mapels->get($m->mapel_id);
                            $k_obj = $kelases->get($m->kelas_id);
                            if ($m_obj && $k_obj) {
                                if (!isset($mapelsGrouped[$m_obj->nama_mapel])) {
                                    $mapelsGrouped[$m_obj->nama_mapel] = [];
                                }
                                if (!in_array($k_obj->nama_kelas, $mapelsGrouped[$m_obj->nama_mapel])) {
                                    $mapelsGrouped[$m_obj->nama_mapel][] = $k_obj->nama_kelas;
                                }
                            }
                        }
                    @endphp
                    @foreach($mapelsGrouped as $mapelName => $kelasNames)
                    <li class="list-group-item border-left-0 border-right-0 px-0 {{ $loop->first ? 'border-top-0' : '' }}">
                        <b class="text-dark">{{ $mapelName }}</b> <a class="float-right text-muted">{{ implode(', ', $kelasNames) }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Card Kelengkapan dokumen -->
        <div class="card border-0 mb-4 shadow-sm assessment-card" style="border-radius: 16px; background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mr-4 shadow-sm" style="width: 60px; height: 60px; font-size: 26px;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1 text-primary">Kelengkapan Dokumen Guru</h5>
                        <p class="text-muted small mb-0" style="color: #5c6c7c !important;">Periksa Dokumen Administrasi, Perangkat Pembelajaran, dan Repositori.</p>
                    </div>
                </div>
                <a href="{{ route('kepala.penilaian.kelengkapan', $guru->id) }}" class="btn btn-primary font-weight-bold shadow-sm px-4 py-2" style="border-radius: 8px;">
                    <i class="fas fa-search mr-2"></i> Periksa Kelengkapan
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm flex-wrap gap-3" style="border-radius: 16px; border: 1px solid #eaeaea;">
            <div class="d-flex align-items-center">
                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; font-size: 20px;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h5 class="font-weight-bold mb-0 text-dark">Penilaian Kinerja</h5>
                    <p class="text-muted small mb-0">Evaluasi PKG per mapel yang diajar.</p>
                </div>
            </div>
        </div>

        <!-- Card 1: Penilaian Kinerja Guru (Looping per Mapel Kelas) -->
        @forelse($mengajarAssignments as $assignment)
            @php
                $mapel = $mapels->get($assignment->mapel_id);
                $kelas = $kelases->get($assignment->kelas_id);
            @endphp
            @if($mapel && $kelas)
            <div class="card mb-3 shadow-sm border-0 assessment-card" style="border-radius: 16px; border: 1px solid #eaeaea !important;">
                <div class="card-body d-flex flex-row align-items-center p-4">
                    <div class="d-flex align-items-center flex-grow-1 pr-3">
                        @php
                            $key = $mapel->id . '-' . $kelas->id;
                            $completed = isset($completedCounts[$key]) ? $completedCounts[$key] : 0;
                            $percentage = ($completed / 7) * 100;
                        @endphp
                        <div class="position-relative d-flex justify-content-center align-items-center shadow-sm" style="width: 70px; height: 70px; border-radius: 50%; background: conic-gradient(#007bff {{ $percentage }}%, #dee2e6 0); flex-shrink: 0;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: white;" class="d-flex justify-content-center align-items-center">
                                <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">{{ $completed }}/7</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h5 class="font-weight-bold mb-1 text-dark" style="font-size: 1.15rem;">{{ $mapel->nama_mapel }} <span class="badge badge-light border text-muted px-2 ml-1" style="border-radius: 6px;">{{ $kelas->nama_kelas }}</span></h5>
                            <span class="text-muted d-block mt-1" style="font-size: 0.95rem; line-height: 1.4;">Lakukan penilaian kinerja mengajar untuk kelas ini.</span>
                        </div>
                    </div>
                    <div class="text-right d-flex flex-column gap-2" style="width: 140px; flex-shrink: 0;">
                        @if($completed == 7)
                            <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-secondary font-weight-bold w-100 mb-2 shadow-sm" style="border-radius: 8px;"><i class="fas fa-eye mr-1"></i> Detail</a>
                            <a href="{{ route('kepala.penilaian.cetakPkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-light border text-primary font-weight-bold w-100 shadow-sm" style="border-radius: 8px;" target="_blank"><i class="fas fa-file-pdf mr-1 text-danger"></i> PDF</a>
                        @else
                            <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-primary font-weight-bold w-100 shadow-sm py-2" style="border-radius: 8px;"><i class="fas fa-edit mr-1"></i> Nilai</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="alert bg-white border shadow-sm text-center py-5" style="border-radius: 16px;">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Guru ini belum memiliki penugasan mengajar.</h5>
            </div>
        @endforelse
    </div>
</div>
@stop

@section('css')
<style>
    .assessment-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .assessment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
        border-color: #007bff !important;
    }
</style>
@stop
