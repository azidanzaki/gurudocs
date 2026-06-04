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
    function renderStatus($pg) {
        if (!$pg) return '<span class="badge badge-secondary">Belum Diisi</span>';
        $origStatus = $pg->status;
        $id = $pg->id;
        
        if ($pg->is_completed) {
            return '<span class="badge badge-success" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Selesai</span>';
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
                            $isPastDeadline = $tenggatDate && now()->startOfDay()->gt($tenggatDate->startOfDay());
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                <strong>{{ $template->nama_perangkat }}</strong>
                            </td>
                            <td class="text-center align-middle">{!! renderStatus($pg) !!}</td>
                            <td class="text-center align-middle text-muted">
                                {{ $tenggatDate ? $tenggatDate->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="text-center align-middle">
                                @if($pg && !$pg->is_completed)
                                    <button class="btn btn btn-success btn-submit-doc" data-id="{{ $pg->id }}" data-past-deadline="{{ $isPastDeadline ? '1' : '0' }}" {!! $isPastDeadline ? 'style="background-color: #6c757d; border-color: #6c757d;"' : '' !!}>
                                        <i class="fas fa-paper-plane"></i> Kirim Perangkat
                                    </button>
                                @elseif($pg && $pg->is_completed)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Terkirim</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($pg)
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn btn-primary {{ $pg->is_completed ? 'd-none' : '' }}" id="btn_edit_{{ $pg->id }}">
                                        <i class="fas fa-edit"></i> {{ !$pg->isDraft() ? 'Lihat/Edit' : 'Isi' }}
                                    </a>
                                    <button class="btn btn btn-secondary {{ $pg->is_completed ? '' : 'd-none' }}" disabled id="btn_disabled_{{ $pg->id }}" title="Perangkat telah selesai">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </button>
                                    <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn btn-outline-secondary" title="Cetak">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @else
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn btn-primary">
                                        <i class="fas fa-edit"></i> Isi
                                    </a>
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
        
        if (isPastDeadline == '1') {
            alert('Tugas tidak bisa dikumpulkan karna sudah lewat tenggat waktu.');
            return;
        }

        if (!confirm('Yakin ingin mensubmit? Kalau sudah submit tidak bisa edit lagi dan otomatis terkirim.')) {
            return;
        }

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
                    alert('Gagal submit perangkat.');
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
                }
            },
            error: function() {
                alert('Gagal mensubmit status perangkat.');
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
            }
        });
    });
});
</script>
@endpush