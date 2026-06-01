@extends('adminlte::page')

@section('title', 'Perangkat — ' . $kelas->nama_kelas_simple)

@section('content_header')
    <h1>Perangkat &mdash; {{ $mapel->nama_mapel }} / {{ $kelas->nama_kelas_simple }}</h1>
@stop

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('guru.perangkat.show', $mapel->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $tahunan = $templates->where('frekuensi', 'tahunan');
    $semesteran = $templates->where('frekuensi', 'semesteran');
    $perBab = $templates->where('frekuensi', 'per_bab');
@endphp

<!-- TAHUNAN -->
@if($tahunan->count() > 0)
<h4 class="mb-3 mt-4 text-primary"><i class="fas fa-calendar-alt"></i> Perangkat Tahunan</h4>
<div class="row">
    @foreach($tahunan as $template)
    @php
        $pgs = $progress[$template->id] ?? collect();
        $pg = $pgs->first();
    @endphp
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; flex-shrink:0;">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">{{ $template->nama_perangkat }}</h5>
                        @if($pg)
                            <span class="badge badge-{{ $pg->statusBadgeClass() }}">{{ ucfirst($pg->status) }}</span>
                        @else
                            <span class="badge badge-light border">Belum dimulai</span>
                        @endif
                    </div>
                </div>
                @if($template->deskripsi)
                    <p class="text-muted small mb-3">{{ $template->deskripsi }}</p>
                @endif
                @if($pg)
                    <div class="form-group mb-3 mt-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input toggle-complete-checkbox" id="check_{{ $pg->id }}" data-id="{{ $pg->id }}" {{ $pg->is_completed ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-normal text-muted" for="check_{{ $pg->id }}">
                                {{ $pg->is_completed ? 'Perangkat Selesai (Ceklis)' : 'Tandai Perangkat Selesai' }}
                            </label>
                        </div>
                    </div>
                @endif
                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester=1" class="btn btn-primary flex-fill">
                        <i class="fas fa-edit"></i> {{ $pg && !$pg->isDraft() ? 'Lihat' : 'Isi Perangkat' }}
                    </a>
                    @if($pg)
                    <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-print"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- SEMESTERAN -->
@if($semesteran->count() > 0)
<h4 class="mb-3 mt-4 text-success"><i class="fas fa-calendar-half-o"></i> Perangkat Semesteran</h4>
<div class="row">
    @foreach($semesteran as $template)
    @php
        $pgs = $progress[$template->id] ?? collect();
        $pgSem1 = $pgs->where('semester', 1)->first();
        $pgSem2 = $pgs->where('semester', 2)->first();
    @endphp
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; flex-shrink:0;">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 font-weight-bold">{{ $template->nama_perangkat }}</h5>
                        <p class="text-muted small mb-0">{{ $template->deskripsi }}</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Semester 1 -->
                    <div class="col-6 border-right">
                        <h6 class="font-weight-bold text-center">Semester 1</h6>
                        <div class="text-center mb-2">
                            @if($pgSem1)
                                <span class="badge badge-{{ $pgSem1->statusBadgeClass() }}">{{ ucfirst($pgSem1->status) }}</span>
                            @else
                                <span class="badge badge-light border">Belum dimulai</span>
                            @endif
                        </div>
                        @if($pgSem1)
                            <div class="custom-control custom-checkbox text-center mb-2">
                                <input type="checkbox" class="custom-control-input toggle-complete-checkbox" id="check_{{ $pgSem1->id }}" data-id="{{ $pgSem1->id }}" {{ $pgSem1->is_completed ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal text-muted" for="check_{{ $pgSem1->id }}">Selesai</label>
                            </div>
                        @endif
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester=1" class="btn btn-sm btn-success flex-fill">
                                <i class="fas fa-edit"></i> Isi
                            </a>
                            @if($pgSem1)
                            <a href="{{ route('guru.perangkat.print', $pgSem1->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Semester 2 -->
                    <div class="col-6">
                        <h6 class="font-weight-bold text-center">Semester 2</h6>
                        <div class="text-center mb-2">
                            @if($pgSem2)
                                <span class="badge badge-{{ $pgSem2->statusBadgeClass() }}">{{ ucfirst($pgSem2->status) }}</span>
                            @else
                                <span class="badge badge-light border">Belum dimulai</span>
                            @endif
                        </div>
                        @if($pgSem2)
                            <div class="custom-control custom-checkbox text-center mb-2">
                                <input type="checkbox" class="custom-control-input toggle-complete-checkbox" id="check_{{ $pgSem2->id }}" data-id="{{ $pgSem2->id }}" {{ $pgSem2->is_completed ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal text-muted" for="check_{{ $pgSem2->id }}">Selesai</label>
                            </div>
                        @endif
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester=2" class="btn btn-sm btn-success flex-fill">
                                <i class="fas fa-edit"></i> Isi
                            </a>
                            @if($pgSem2)
                            <a href="{{ route('guru.perangkat.print', $pgSem2->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- PER BAB -->
@if($perBab->count() > 0)
<h4 class="mb-3 mt-4 text-warning"><i class="fas fa-list-ol"></i> Perangkat Per Bab</h4>
<div class="row">
    @foreach($perBab as $template)
    @php
        $pgs = $progress[$template->id] ?? collect();
    @endphp
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:52px; height:52px; flex-shrink:0;">
                        <i class="fas fa-layer-group text-white"></i>
                    </div>
                    <div class="ml-3 flex-fill">
                        <h5 class="mb-0 font-weight-bold">{{ $template->nama_perangkat }}</h5>
                        <p class="text-muted small mb-0">{{ $template->deskripsi }}</p>
                    </div>
                    <div>
                        <!-- Add new bab -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle text-white" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-plus"></i> Tambah Bab
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester=1&bab={{ $pgs->where('semester', 1)->max('bab') + 1 }}">Semester 1 (Bab {{ $pgs->where('semester', 1)->max('bab') + 1 }})</a>
                                <a class="dropdown-item" href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester=2&bab={{ $pgs->where('semester', 2)->max('bab') + 1 }}">Semester 2 (Bab {{ $pgs->where('semester', 2)->max('bab') + 1 }})</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($pgs->isEmpty())
                    <p class="text-muted text-center py-3">Belum ada bab yang dibuat.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Semester</th>
                                    <th>Bab Ke</th>
                                    <th>Status</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pgs->sortBy(['semester', 'bab']) as $pg)
                                <tr>
                                    <td>Semester {{ $pg->semester }}</td>
                                    <td>Bab {{ $pg->bab }}</td>
                                    <td><span class="badge badge-{{ $pg->statusBadgeClass() }}">{{ ucfirst($pg->status) }}</span></td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" class="custom-control-input toggle-complete-checkbox" id="check_{{ $pg->id }}" data-id="{{ $pg->id }}" {{ $pg->is_completed ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="check_{{ $pg->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id]) }}?semester={{ $pg->semester }}&bab={{ $pg->bab }}" class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-print"></i>
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
    @endforeach
</div>
@endif

@stop

@push('js')
<script>
$(document).ready(function() {
    $('.toggle-complete-checkbox').change(function() {
        var checkbox = $(this);
        var pgId = checkbox.data('id');
        var isChecked = checkbox.is(':checked');
        var label = checkbox.siblings('label');
        var isTable = label.text() === ""; // if label is empty, it's inside the table (per bab)

        $.ajax({
            url: '/guru/perangkat/' + pgId + '/toggle-complete',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_completed) {
                        if (!isTable) label.text(label.text().includes('Selesai') && label.text().includes('Ceklis') ? label.text() : (label.text() === 'Selesai' ? 'Selesai' : 'Perangkat Selesai (Ceklis)'));
                        checkbox.prop('checked', true);
                    } else {
                        if (!isTable) label.text(label.text().includes('Tandai') ? label.text() : (label.text() === 'Selesai' ? 'Selesai' : 'Tandai Perangkat Selesai'));
                        checkbox.prop('checked', false);
                    }
                }
            },
            error: function() {
                checkbox.prop('checked', !isChecked);
                alert('Gagal memperbarui status perangkat.');
            }
        });
    });
});
</script>
@endpush