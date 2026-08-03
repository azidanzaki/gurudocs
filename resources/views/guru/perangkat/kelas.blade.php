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
    <form action="{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id]) }}" method="GET" class="d-flex">
        <label class="mr-2 mb-0 align-self-center" style="white-space: nowrap;">
    Tahun Ajaran:
</label>
        <select name="tahun_ajaran" class="form-control form-control" onchange="this.form.submit()">
            @foreach($tahunAjarans as $ta)
                <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>
                    {{ $ta->nama }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    function renderStatus($pg, $isPastDeadline = false) {
        if (!$pg) {
            return $isPastDeadline 
                ? '<span class="badge badge-danger">Tidak Terkirim<br><small>(Melewati Tenggat)</small></span>'
                : '<span class="badge badge-secondary">Belum Diisi</span>';
        }
        $origStatus = $pg->status;
        $id = $pg->id;
        
        if ($pg->is_completed) {
            return '<span class="badge badge-success" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Selesai</span>';
        }

        if ($isPastDeadline) {
            return '<span class="badge badge-danger" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Tidak Terkirim<br><small>(Melewati Tenggat)</small></span>';
        }
        
        if ($origStatus === 'draft') {
            return '<span class="badge badge-warning" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Dalam Proses</span>';
        }
        if ($origStatus === 'submitted' || $origStatus === 'approved') {
            return '<span class="badge badge-success" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Selesai</span>';
        }
        if ($origStatus === 'rejected') {
            return '<span class="badge badge-danger" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Ditolak</span>';
        }
        return '<span class="badge badge-secondary" id="badge_'.$id.'" data-original-status="'.$origStatus.'">'.ucfirst($origStatus).'</span>';
    }
    $no = 1;
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="5%">No.</th>
                        <th width="35%">Nama Perangkat</th>
                        <th width="15%">Status</th>
                        <th width="15%">Tenggat Waktu</th>
                        <th width="15%">Kirim Perangkat</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        @php
                            $pgs = $progress[$template->id] ?? collect();
                            $pg = $pgs->first();
                            $tenggat = $template->tenggatWaktus->first();
                            $tenggatDate = $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu) : null;
                            $isPastDeadline = $tenggatDate && now()->gt($tenggatDate);
                            
                            if ($tenggatDate) {
                                $now = \Carbon\Carbon::now();
                                if ($isPastDeadline) {
                                    $sisaText = 'Sudah lewat';
                                } else {
                                    $diff = $tenggatDate->diff($now);
                                    $days = $diff->d;
                                    $hours = $diff->h;
                                    $minutes = $diff->i;
                                    $sisaParts = [];
                                    if ($days > 0) $sisaParts[] = $days . ' hari';
                                    if ($hours > 0) $sisaParts[] = $hours . ' jam';
                                    if ($minutes > 0) $sisaParts[] = $minutes . ' menit';
                                    $sisaText = 'sisa ' . implode(' ', $sisaParts);
                                    if(empty($sisaParts)) $sisaText = 'sisa kurang dari 1 menit';
                                }
                                $tenggatInfo = $tenggatDate->translatedFormat('d F Y H:i') . ' <br><small class="text-muted">(' . $sisaText . ')</small>';
                            } else {
                                $tenggatInfo = '<span class="text-warning"><i class="fas fa-info-circle"></i> Belum diatur</span>';
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                <strong>{{ $template->nama_perangkat }}</strong>
                            </td>
                            <td class="text-center align-middle">{!! renderStatus($pg, $isPastDeadline) !!}</td>
                            <td class="text-center align-middle">
                                {!! $tenggatInfo !!}
                            </td>
                            <td class="text-center align-middle">
                                @if($pg && !$pg->is_completed)
                                    <button class="btn btn-success btn-submit-doc" 
                                            data-id="{{ $pg->id }}" 
                                            data-past-deadline="{{ $isPastDeadline ? '1' : '0' }}"
                                            data-no-deadline="{{ is_null($tenggatDate) ? '1' : '0' }}"
                                            {!! $isPastDeadline ? 'disabled style="background-color: #6c757d; border-color: #6c757d;"' : '' !!}>
                                        <i class="fas fa-paper-plane"></i> Kirim Perangkat
                                    </button>
                                @elseif($pg && $pg->is_completed)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Terkirim</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($pg)
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-primary {{ $pg->is_completed || $isPastDeadline ? 'd-none' : '' }}" id="btn_edit_{{ $pg->id }}">
                                        <i class="fas fa-edit"></i> {{ !$pg->isDraft() ? 'Lihat/Edit' : 'Isi' }}
                                    </a>
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-secondary {{ $pg->is_completed || $isPastDeadline ? '' : 'd-none' }}" id="btn_edit_{{ $pg->id }}">
                                        <i class="fas fa-eye"></i> {{ !$pg->isDraft() ? 'Lihat' : 'Isi' }}
                                    </a>
                                    <!-- <button class="btn btn-secondary {{ $pg->is_completed || $isPastDeadline ? '' : 'd-none' }}" disabled id="btn_disabled_{{ $pg->id }}" title="Perangkat telah selesai atau dikunci">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </button> -->
                                    <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn-outline-secondary" title="Cetak">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @else
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-primary {{ $isPastDeadline ? 'd-none' : '' }}">
                                        <i class="fas fa-edit"></i> Isi
                                    </a>
                                    <button class="btn btn-secondary {{ $isPastDeadline ? '' : 'd-none' }}" disabled title="Terkunci karena melewati tenggat">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
$(document).ready(function() {
    $('.btn-submit-doc').click(function() {
        var btn = $(this);
        var pgId = btn.data('id');
        var isPastDeadline = btn.data('past-deadline');
        var isNoDeadline = btn.data('no-deadline');

            // Allow submission even if deadline not set

        Swal.fire({
            title: 'Yakin ingin mensubmit?',
            text: 'Kalau sudah submit tidak bisa edit lagi dan otomatis terkirim.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Submit!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button during process
                btn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i>');

                $.ajax({
                    url: '/guru/perangkat/' + pgId + '/toggle-complete',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        force_complete: true // flag to ensure it only completes
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            Swal.fire('Gagal', 'Gagal submit perangkat.', 'error');
                            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mensubmit status perangkat.', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
                    }
                });
            }
        });
    });
});
</script>
@endpush