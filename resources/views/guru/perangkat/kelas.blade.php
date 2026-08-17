@extends('adminlte::page')

@section('title', 'Perangkat — ' . $kelas->nama_kelas_simple)

@section('content_header')
    <h1>Perangkat &mdash; {{ $mapel->nama_mapel }} / {{ $kelas->nama_kelas_simple }}</h1>
@stop

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <a href="{{ route('guru.perangkat.index') }}" class="btn btn-light border px-3 shadow-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    
    <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
        <label class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
        <form action="{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id]) }}" method="GET" class="mb-0">
            <select name="tahun_ajaran" class="form-control border-0 bg-light mb-0" style="border-radius: 8px; font-weight: bold; min-width: 150px;" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>
                        {{ $ta->nama }} @if(in_array($ta->nama, $teachingYears)) (Mengajar) @endif
                    </option>
                @endforeach
            </select>
        </form>
    </div>
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
        if ($origStatus === 'revisi') {
            return '<div class="d-flex justify-content-center align-items-center"><span class="badge badge-info" id="badge_'.$id.'" data-original-status="'.$origStatus.'">Revisi</span></div>';
        }
        return '<span class="badge badge-secondary" id="badge_'.$id.'" data-original-status="'.$origStatus.'">'.ucfirst($origStatus).'</span>';
    }
    $no = 1;
@endphp

@if($isTeaching)
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th class="border-0 px-4 py-3" width="5%">No.</th>
                        <th class="border-0 py-3 text-left" width="35%">Nama Perangkat</th>
                        <th class="border-0 py-3" width="15%">Status</th>
                        <th class="border-0 py-3" width="15%">Tenggat Waktu</th>
                        <th class="border-0 py-3" width="15%">Kumpulkan Perangkat</th>
                        <th class="border-0 py-3" width="15%">Aksi</th>
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
                                $tenggatInfo = '<span class="text-warning"><i class="fas fa-info-circle"></i> Belum ada batas waktu</span>';
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle py-3 px-4">{{ $no++ }}</td>
                            <td class="align-middle py-3">
                                <strong>{{ $template->nama_perangkat }}</strong>
                            </td>
                            <td class="text-center align-middle py-3">{!! renderStatus($pg, $isPastDeadline) !!}</td>
                            <td class="text-center align-middle py-3">
                                {!! $tenggatInfo !!}
                            </td>
                            <td class="text-center align-middle py-3">
                                @if($pg && !$pg->is_completed)
                                    <button class="btn btn-sm btn-success btn-submit-doc shadow-sm px-3" style="border-radius: 6px;"
                                            data-id="{{ $pg->id }}" 
                                            data-past-deadline="{{ $isPastDeadline ? '1' : '0' }}"
                                            data-no-deadline="{{ is_null($tenggatDate) ? '1' : '0' }}"
                                            {!! $isPastDeadline ? 'disabled style="background-color: #6c757d; border-color: #6c757d; border-radius: 6px;"' : '' !!}>
                                        <i class="fas fa-paper-plane mr-1"></i> Kumpulkan
                                    </button>
                                @elseif($pg && $pg->is_completed)
                                    <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Terkirim</span>
                                @endif
                            </td>
                            <td class="text-center align-middle py-3">
                                @if($pg)
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-sm btn-primary shadow-sm px-3 {{ $pg->is_completed || $isPastDeadline ? 'd-none' : '' }}" style="border-radius: 6px;" id="btn_edit_{{ $pg->id }}">
                                        <i class="fas fa-edit"></i> {{ !$pg->isDraft() ? 'Lihat/Edit' : 'Isi' }}
                                    </a>
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-sm btn-secondary shadow-sm px-3 {{ $pg->is_completed || $isPastDeadline ? '' : 'd-none' }}" style="border-radius: 6px;" id="btn_edit_{{ $pg->id }}">
                                        <i class="fas fa-eye"></i> {{ !$pg->isDraft() ? 'Lihat' : 'Isi' }}
                                    </a>
                                    @if($pg->status !== 'revisi')
                                    <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm px-3" style="border-radius: 6px;" title="Cetak" onclick="printDoc('{{ route('guru.perangkat.print', $pg->id) }}')">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    @endif
                                @else
                                    <a href="{{ route('guru.perangkat.edit', [$mapel->id, $kelas->id, $template->id, 'tahun_ajaran' => $selectedTahun]) }}" class="btn btn-sm btn-primary shadow-sm px-3 {{ $isPastDeadline ? 'd-none' : '' }}" style="border-radius: 6px;">
                                        <i class="fas fa-edit"></i> Isi
                                    </a>
                                    <button class="btn btn-sm btn-secondary shadow-sm px-3 {{ $isPastDeadline ? '' : 'd-none' }}" style="border-radius: 6px;" disabled title="Terkunci karena melewati tenggat">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Catatan Revisi -->
                        @if($pg && $pg->status === 'revisi')
                        <div class="modal fade" id="catatanModal_{{ $pg->id }}" tabindex="-1" role="dialog" aria-labelledby="catatanModalLabel_{{ $pg->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="catatanModalLabel_{{ $pg->id }}">Catatan Revisi - {{ $template->nama_perangkat }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!! nl2br(e($pg->catatan_revisi ?? 'Tidak ada catatan.')) !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm border-0" style="border-radius: 16px;">
    <div class="card-body text-center py-5">
        <div class="mb-4 text-muted">
            <i class="fas fa-exclamation-circle fa-4x text-warning"></i>
        </div>
        <h4 class="font-weight-bold text-dark mb-3">Tidak Ada Data</h4>
        <p class="text-muted mb-0" style="font-size: 1.1rem;">
            Tidak ada data perangkat pembelajaran karena Anda tidak mengajar mata pelajaran <strong>{{ $mapel->nama_mapel }}</strong> di kelas <strong>{{ $kelas->nama_kelas_simple }}</strong> pada tahun ajaran <strong>{{ $selectedTahun }}</strong>.
        </p>
    </div>
</div>
@endif

<iframe id="printFrame" style="position: absolute; width: 1px; height: 1px; visibility: hidden; border: 0;"></iframe>

@stop

@push('js')
<script>
function printDoc(url) {
    var frame = document.getElementById('printFrame');
    frame.src = url;
    frame.onload = function() {
        frame.contentWindow.print();
    };
}

$(document).ready(function() {
    $('.btn-submit-doc').click(function() {
        var btn = $(this);
        var pgId = btn.data('id');
        var isPastDeadline = btn.data('past-deadline');
        var isNoDeadline = btn.data('no-deadline');

            // Allow submission even if deadline not set

        Swal.fire({
            title: 'Kumpulkan Perangkat?',
            text: 'Perangkat yang sudah di kirim tidak bisa di ubah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Kumpulkan',
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