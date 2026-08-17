@extends('adminlte::page')

@section('title', 'Perangkat Pembelajaran')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-3">
    <h1 class="m-0 text-dark font-weight-bold">Perangkat Pembelajaran</h1>
    <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
        <label for="tahun_ajaran_id" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
        <form action="{{ route('guru.perangkat.index') }}" method="GET" class="mb-0">
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

<style>
.subject-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
}
.subject-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
}
.subject-icon-box {
    width: 65px;
    height: 65px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #198754, #20c997);
    color: white;
    box-shadow: 0 6px 15px rgba(25, 135, 84, 0.3);
    transition: transform 0.3s ease;
}
.subject-card:hover .subject-icon-box {
    transform: scale(1.08) rotate(5deg);
}
.btn-siapkan {
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
    padding: 10px 15px;
}
.subject-card:hover .btn-siapkan {
    background-color: #157347;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(21, 115, 71, 0.3);
}
.badge-class {
    background-color: #e8f5e9;
    color: #2e7d32;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.85rem;
}
</style>

<div class="mb-4 d-flex justify-content-end">
    <a href="{{ route('guru.perangkat.history') }}" class="btn btn-info shadow-sm d-flex align-items-center">
        <i class="fas fa-history mr-2"></i> History Dokumen
    </a>
</div>

<div class="row">
    @forelse($combinations as $combo)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 subject-card h-100">
            <div class="card-body d-flex flex-column p-4">
                <div class="d-flex align-items-start mb-4">
                    <div class="subject-icon-box flex-shrink-0">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <div class="ml-3 mt-1 flex-grow-1">
                        <h5 class="mb-2 font-weight-bold text-dark" style="line-height: 1.3;">
                            {{ $combo->mapel->nama_mapel }}
                        </h5>
                        <div class="d-flex flex-wrap gap-1 mb-3 align-items-center">
                            <span class="badge-class mr-1">
                                <i class="fas fa-chalkboard mr-1"></i> Kelas {{ $combo->kelas->nama_kelas_simple }}
                            </span>
                            <span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 6px; font-size: 0.8rem; font-weight: 500;">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $selectedTahunName }}
                            </span>
                        </div>
                        <p class="text-muted small mb-1" style="font-size: 0.9rem;">
                            <i class="fas fa-tasks mr-1"></i> {{ $combo->completed_count }}/{{ $totalTemplates }} perangkat selesai
                        </p>
                        @if($combo->revision_count > 0)
                        <p class="text-danger small font-weight-bold mb-0" style="font-size: 0.9rem;">
                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $combo->revision_count }} perangkat perlu di revisi
                        </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('guru.perangkat.kelas', [$combo->mapel->id, $combo->kelas->id]) }}"
                   class="btn btn-success btn-block btn-siapkan mt-auto d-flex justify-content-between align-items-center">
                    <span>Siapkan Perangkat</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486740.png" width="140" alt="Not Found" class="opacity-75" style="filter: grayscale(20%);">
                </div>
                <h4 class="font-weight-bold text-dark mb-3">Anda Belum Memiliki Mata Pelajaran & Kelas</h4>
                <p class="text-muted mb-0" style="font-size: 1.1rem;">
                    Minta admin untuk menambahkan mata pelajaran dan kelas yang Anda ampu ke dalam sistem.
                </p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@stop