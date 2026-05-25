@extends('adminlte::page')

@section('title', 'History Perangkat — ' . $kelas->nama_kelas_simple)

@section('content_header')
    <h1>History Perangkat &mdash; {{ $mapel->nama_mapel }} / {{ $kelas->nama_kelas_simple }}</h1>
@stop

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id]) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0 font-weight-bold">
            <i class="fas fa-history text-muted mr-2"></i> Pilih Tahun
        </h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('guru.perangkat.history', [$mapel->id, $kelas->id]) }}" class="form-inline">
            <div class="form-group mr-2">
                <select name="tahun" class="form-control" onchange="this.form.submit()">
                    @if($availableYears->isEmpty())
                        <option value="">Tidak ada history</option>
                    @else
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year ?? 'Sebelumnya' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <noscript><button type="submit" class="btn btn-primary">Pilih</button></noscript>
        </form>
    </div>
</div>

@if($selectedYear || $availableYears->contains(null))
<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-light">
        <h4 class="card-title mb-0 font-weight-bold">History Dokumen - {{ $selectedYear ?? 'Sebelumnya' }}</h4>
    </div>
    <div class="card-body">
        @if($historyItems->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                <p class="mb-0">Tidak ada dokumen pada tahun ini.</p>
            </div>
        @else
            <div class="row">
                @foreach($historyItems as $item)
                    <div class="col-md-4 mb-3">
                        <div class="card border h-100">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="mb-0 font-weight-bold">{{ $item->template->nama_perangkat }}</h6>
                                    <span class="badge badge-{{ $item->is_completed ? 'success' : 'secondary' }}">
                                        {{ $item->is_completed ? 'Selesai' : 'Draft' }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-2">Tahun Ajaran: {{ $item->tahun_ajaran }}</p>
                                <a href="{{ route('guru.perangkat.print', $item->id) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                    <i class="fas fa-print"></i> Cetak / Preview
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif

@stop
