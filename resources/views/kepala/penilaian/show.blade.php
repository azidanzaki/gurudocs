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
                            $mapel = \App\Models\Mapel::find($m->mapel_id);
                            $kelas = \App\Models\Kelas::find($m->kelas_id);
                            if ($mapel && $kelas) {
                                $mapelsGrouped[$mapel->nama_mapel][] = $kelas->nama_kelas;
                            }
                        }
                    @endphp
                    @foreach($mapelsGrouped as $mapelName => $kelases)
                    <li class="list-group-item">
                        <b>{{ $mapelName }}</b> <a class="float-right">{{ implode(', ', $kelases) }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Penilaian Guru (Segera Hadir)</h3>
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5>Fitur Penilaian Belum Tersedia</h5>
                <p class="text-muted">Untuk saat ini, Anda baru bisa melihat profil guru.</p>
            </div>
        </div>
    </div>
</div>
@stop
