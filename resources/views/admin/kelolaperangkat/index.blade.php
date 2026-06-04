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

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('success') }}
    </div>
@endif

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
                    $tenggatDate = $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->translatedFormat('d F Y') : 'Belum diatur';
                @endphp
                <tr>
                    <td class="align-middle">{{ $template->nama_perangkat }}</td>
                    <td class="align-middle">
                        {{ $tenggatDate }}
                    </td>
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-warning mr-1" data-toggle="modal" data-target="#modalTenggat{{ $template->id }}">
                            <i class="fas fa-calendar-alt"></i> {{ $tenggat ? 'Ubah Tenggat' : 'Atur Tenggat' }}
                        </button>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalGuru{{ $template->id }}">
                            <i class="fas fa-users"></i> Lihat List Guru
                        </button>

                        {{-- Modal Atur Tenggat --}}
                        <div class="modal fade" id="modalTenggat{{ $template->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.kelolaperangkat.updateTenggat') }}" method="POST">
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
                                                <label>Tanggal Tenggat Waktu</label>
                                                <input type="date" name="tenggat_waktu" class="form-control" value="{{ $tenggat ? $tenggat->tenggat_waktu : '' }}" required>
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
                                                                    <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank" class="btn btn-xs btn-primary mb-1">
                                                                        <i class="fas fa-file-pdf"></i> Lihat Dokumen
                                                                    </a><br>
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
<style>
    .gap-2 { gap: 0.5rem; }
</style>
@endpush