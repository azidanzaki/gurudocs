@extends('adminlte::page')

@section('title', 'Perangkat — ' . $kelas->nama_kelas)

@section('content_header')
    <h1>Perangkat &mdash; {{ $mapel->nama_mapel }} / {{ $kelas->nama_kelas }}</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('guru.perangkat.show', $mapel->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">

    @forelse($templates as $template)

    @php $pg = $progress[$template->id] ?? null; @endphp

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">

                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; flex-shrink:0;">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">{{ $template->nama_perangkat }}</h5>
                        @if($pg)
                            <span class="badge badge-{{ $pg->statusBadgeClass() }}">
                                {{ ucfirst($pg->status) }}
                            </span>
                        @else
                            <span class="badge badge-light border">Belum dimulai</span>
                        @endif
                    </div>
                </div>

                @if($template->deskripsi)
                    <p class="text-muted small mb-3">{{ $template->deskripsi }}</p>
                @endif

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}"
                       class="btn btn-success flex-fill">
                        <i class="fas fa-edit"></i>
                        {{ $pg && !$pg->isDraft() ? 'Lihat' : 'Isi Perangkat' }}
                    </a>

                    @if($pg)
                    <a href="{{ route('guru.perangkat.print', $pg->id) }}"
                       target="_blank"
                       class="btn btn-outline-secondary">
                        <i class="fas fa-print"></i>
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5>Belum ada template perangkat tersedia.</h5>
                <p class="text-muted">Minta admin untuk menambahkan template perangkat.</p>
            </div>
        </div>
    </div>
    @endforelse

</div>

@stop