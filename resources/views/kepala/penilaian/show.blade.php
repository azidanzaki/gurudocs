@extends('adminlte::page')

@section('title', 'Profil Guru - ' . $guru->name)

@section('content_header')
    <h1>Profil Guru</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('kepala.penilaian') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img src="{{ $guru->foto ? asset('storage/' . $guru->foto) : asset('images/logomts.png') }}" class="img-circle elevation-2 mb-3"
                        style="width:160px; height:160px; object-fit:cover;">
                </div>

                <h3 class="profile-username text-center">
                    {{ $guru->name }}
                </h3>

                <p class="text-muted text-center">
                    Guru<br><small>NIP: {{ $guru->nip }}</small>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $guru->email ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>No. HP</b> <a class="float-right">{{ $guru->no_hp ?? '-' }}</a>
                    </li>
                </ul>

                <ul class="list-group list-group-unbordered mb-3">
                    @php
                        $mengajar = $guru->mengajar();
                        $mapelsGrouped = [];
                        foreach ($mengajar as $m) {
                            $m_obj = \App\Models\Mapel::find($m->mapel_id);
                            $k_obj = \App\Models\Kelas::find($m->kelas_id);
                            if ($m_obj && $k_obj) {
                                $simpleKelas = trim(preg_replace('/\d+$/', '', $k_obj->nama_kelas));
                                if (!isset($mapelsGrouped[$m_obj->nama_mapel])) {
                                    $mapelsGrouped[$m_obj->nama_mapel] = [];
                                }
                                if (!in_array($simpleKelas, $mapelsGrouped[$m_obj->nama_mapel])) {
                                    $mapelsGrouped[$m_obj->nama_mapel][] = $simpleKelas;
                                }
                            }
                        }
                    @endphp
                    @foreach($mapelsGrouped as $mapelName => $kelasNames)
                    <li class="list-group-item">
                        <b>{{ $mapelName }}</b> <a class="float-right">{{ implode(', ', $kelasNames) }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h5 class="font-weight-bold mb-0">Rincian Penilaian Kinerja:</h5>
            <form action="{{ route('kepala.penilaian.show', $guru->id) }}" method="GET" class="form-inline">
                <label for="tahun_ajaran_id" class="mr-2 mb-0" style="font-size: 0.9rem;">Tahun Ajaran:</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTahunId == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <p class="text-muted mb-4">Lakukan Penilaian Kinerja Guru, periksa kelengkapan dokumen jika diperlukan.</p>

        <!-- Card 1: Penilaian Kinerja Guru (Looping per Mapel Kelas) -->
        @forelse($mengajarAssignments as $assignment)
            @php
                $mapel = $mapels->get($assignment->mapel_id);
                $kelas = $kelases->get($assignment->kelas_id);
            @endphp
            @if($mapel && $kelas)
            <div class="card mb-3 shadow-sm border assessment-card" style="border-radius: 8px;">
                <div class="card-body d-flex flex-row align-items-center p-3">
                    <div class="d-flex align-items-center flex-grow-1 pr-3">
                        @php
                            $key = $mapel->id . '-' . $kelas->id;
                            $completed = isset($completedCounts[$key]) ? $completedCounts[$key] : 0;
                            $percentage = ($completed / 7) * 100;
                        @endphp
                        <div class="position-relative d-flex justify-content-center align-items-center" style="width: 70px; height: 70px; border-radius: 50%; background: conic-gradient(#007bff {{ $percentage }}%, #dee2e6 0); flex-shrink: 0;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: white;" class="d-flex justify-content-center align-items-center">
                                <span class="font-weight-bold text-primary" style="font-size: 1.1rem;">{{ $completed }}/7</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h6 class="font-weight-bold mb-1" style="font-size: 1.1rem;">Penilaian Kinerja Guru: {{ $mapel->nama_mapel }} ({{ $kelas->nama_kelas }})</h6>
                            <span class="text-muted" style="font-size: 0.95rem;">Lakukan penilaian kinerja Guru untuk mata pelajaran {{ $mapel->nama_mapel }} kelas {{ $kelas->nama_kelas }}</span>
                        </div>
                    </div>
                    <div class="text-right" style="width: 120px; flex-shrink: 0;">
                        @if($completed == 7)
                            <a href="{{ route('kepala.penilaian.cetakPkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-outline-primary font-weight-bold w-100 mb-2" style="border-radius: 6px;" target="_blank"><i class="fas fa-file-pdf mr-1"></i> Lihat Dokumen</a>
                            <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-secondary font-weight-bold w-100" style="border-radius: 6px;">Detail</a>
                        @else
                            <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas_id' => $kelas->id, 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-primary font-weight-bold w-100" style="border-radius: 6px;">Nilai</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="alert alert-info shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-info-circle mr-2"></i> Guru ini belum memiliki penugasan mengajar.
            </div>
        @endforelse

        <!-- Card 3: Kelengkapan dokumen -->
        <div class="card shadow-sm border assessment-card" style="border-radius: 8px;">
            <div class="card-body d-flex flex-row align-items-center p-3">
                <div class="flex-grow-1 pr-3 pl-2">
                    <h6 class="font-weight-bold mb-1" style="font-size: 1.1rem;">Kelengkapan dokumen</h6>
                    <span class="text-muted" style="font-size: 0.95rem;">Periksa Kelengkapan Dokumen dan Kegiatan Guru.</span>
                </div>
                <div class="text-right" style="width: 120px; flex-shrink: 0;">
                    <a href="{{ route('kepala.penilaian.kelengkapan', $guru->id) }}" class="btn btn-primary font-weight-bold w-100" style="border-radius: 6px;">Periksa</a>
                </div>
            </div>
        </div>
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
