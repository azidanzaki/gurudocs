@extends('adminlte::page')

@section('title', 'Kelola Perangkat Pembelajaran')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Kelola Perangkat Pembelajaran</h1>
    <div class="d-flex align-items-center gap-2">

    </div>
</div>
@stop

@section('content')



<div class="card">
    <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">

        <!-- Judul -->
        <h3 class="card-title mb-0">
            Daftar Perangkat & Tenggat Waktu ({{ $selectedTahun }})
        </h3>

        <!-- Dropdown + Button -->
        <div class="d-flex align-items-center">

            <form action="{{ route('admin.kelolaperangkat') }}"
                  method="GET"
                  class="mb-0 mr-2">

                <select name="tahun_ajaran"
                        class="form-control form-control"
                        onchange="this.form.submit()">

                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->nama }}"
                            {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>
                            {{ $ta->nama }}
                        </option>
                    @endforeach

                </select>

            </form>

            <button class="btn btn-primary"
                    data-toggle="modal"
                    data-target="#modalTambahTahun">

                <i class="fas fa-plus"></i>
                Tambah Tahun Ajaran

            </button>

        </div>

    </div>
</div>
    <div class="card-body p-0">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="30%">Nama Perangkat</th>
                    <th width="30%">Tenggat Waktu</th>
                    <th width="40%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                @php
                    $tenggat = $template->tenggatWaktus->first();
                    if ($tenggat) {
                        $parsed = \Carbon\Carbon::parse($tenggat->tenggat_waktu);
                        $now = \Carbon\Carbon::now();
                        if ($parsed->isPast()) {
                            $sisaText = 'Sudah lewat';
                        } else {
                            $diff = $parsed->diff($now);
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
                        $tenggatInfo = $parsed->translatedFormat('d - F - Y H:i') . ' | (' . $sisaText . ')';
                        $btnClass = 'btn-success';
                    } else {
                        $tenggatInfo = '<span class="badge badge-danger">Belum diatur</span>';
                        $btnClass = 'btn-warning';
                    }
                @endphp
                <tr>
                    <td class="align-middle">{{ $template->nama_perangkat }}</td>
                    <td class="align-middle">
                        {!! $tenggatInfo !!}
                    </td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm {{ $btnClass }} mr-1" data-toggle="modal" data-target="#modalTenggat{{ $template->id }}">
                            <i class="fas fa-calendar-alt"></i> {{ $tenggat ? 'Ubah Tenggat' : 'Atur Tenggat' }}
                        </button>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalGuru{{ $template->id }}">
                            <i class="fas fa-users"></i> Lihat List Guru
                        </button>

                        {{-- Modal Atur Tenggat --}}
                        <div class="modal fade" id="modalTenggat{{ $template->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.kelolaperangkat.updateTenggat') }}" method="POST" onsubmit="updateTenggatWaktu({{ $template->id }})">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Atur Tenggat Waktu</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                                            <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                                            <div class="form-group">
                                                <label>Perangkat</label>
                                                <input type="text" class="form-control" value="{{ $template->nama_perangkat }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Tenggat Waktu</label>
                                                <input type="hidden" name="tenggat_waktu" id="tenggat_waktu_{{ $template->id }}" value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('Y-m-d H:i:s') : '' }}">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-primary border-primary"><i class="fas fa-calendar-alt text-white"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control datepicker" id="tanggal_tenggat_{{ $template->id }}" value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('Y-m-d') : date('Y-m-d') }}" placeholder="Pilih Tanggal" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-warning border-warning"><i class="fas fa-clock text-white"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="jam_tenggat_{{ $template->id }}" value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('H:i') : '23:59' }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal List Guru --}}
                        <div class="modal fade" id="modalGuru{{ $template->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title text-white">Daftar Pengumpulan: {{ $template->nama_perangkat }}</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-bordered table-hover mb-0">
                                                <thead class="bg-light sticky-top">
                                                    <tr>
                                                        <th>Nama Guru</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($gurus as $guru)
                                                    @php
                                                        // Cari apakah guru ini punya perangkat untuk template ini
                                                        $pgs = isset($perangkatGurus[$template->id]) ? $perangkatGurus[$template->id]->where('user_id', $guru->id) : collect();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $guru->name }}</td>
                                                        <td>
                                                            @if($pgs->isEmpty())
                                                                <span class="badge badge-secondary">Belum Dibuat</span>
                                                            @else
                                                                @php
                                                                    // Kita anggap kita ambil yang pertama (karena bisa ada banyak jika banyak kelas)
                                                                    // Tapi karena admin perlu melihat per dokumen, kita tampilkan semua kelas yang mereka ajar jika mereka buat
                                                                    // Untuk simpelnya, kita loop di dalam
                                                                @endphp
                                                                @foreach($pgs as $pg)
                                                                    <div class="mb-1">
                                                                        <small class="text-muted">{{ $pg->mapel->nama_mapel }} / {{ $pg->kelas->nama_kelas_simple }}:</small>
                                                                        @if($pg->status == 'submitted' || $pg->is_completed)
                                                                            <span class="badge badge-success">Disubmit</span>
                                                                        @elseif($pg->status == 'draft')
                                                                            <span class="badge badge-warning">Draft</span>
                                                                        @else
                                                                            <span class="badge badge-secondary">{{ $pg->status }}</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td class="align-middle">
                                                            @foreach($pgs as $pg)
                                                                @if($pg->status == 'submitted' || $pg->is_completed)
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn-xs btn-primary mr-1">
                                                                            <i class="fas fa-file-pdf"></i> Lihat
                                                                        </a>
                                                                        <form action="{{ route('admin.kelolaperangkat.reopen', $pg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Buka kembali perangkat ini agar guru bisa merevisinya?');">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-xs btn-warning" title="Buka kembali untuk revisi">
                                                                                <i class="fas fa-unlock"></i> Revisi
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Tahun Ajaran --}}
<div class="modal fade" id="modalTambahTahun" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kelolaperangkat.storeTahunAjaran') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2026/2027" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">
<style>
    .gap-2 { gap: 0.5rem; }
    .clockpicker-popover { z-index: 1060; } /* Ensure it shows above modal */
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
<script>
    function updateTenggatWaktu(id) {
        let tanggal = document.getElementById('tanggal_tenggat_' + id).value;
        let jam = document.getElementById('jam_tenggat_' + id).value;
        if(tanggal && jam) {
            document.getElementById('tenggat_waktu_' + id).value = tanggal + ' ' + jam + ':00';
        }
    }

    $(document).ready(function() {
        $('.datepicker').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true,
            locale: "id"
        });
        $('.clockpicker').clockpicker({
            donetext: 'Selesai',
            autoclose: true
        });
    });
</script>
@endpush