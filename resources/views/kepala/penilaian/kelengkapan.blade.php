@extends('adminlte::page')

@section('title', 'Kelengkapan Dokumen - ' . $guru->name)

@section('content_header')
    <h1>Kelengkapan Dokumen & Kegiatan - {{ $guru->name }}</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Profil
    </a>
</div>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-header border-0 d-flex align-items-center">
        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-file-alt mr-2 text-primary"></i> Dokumen Administrasi / Perangkat Pembelajaran</h3>
        <form action="{{ route('kepala.penilaian.kelengkapan', $guru->id) }}" method="GET" class="d-flex ml-auto" id="filterForm">
            <select name="mapel_id" class="form-control form-control-sm mr-2" onchange="document.getElementById('filterForm').submit();" style="width: 200px; border-radius: 6px;">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($semuaMapels as $mapel)
                    <option value="{{ $mapel->id }}" {{ $selectedMapelId == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                @endforeach
            </select>
            <select name="tahun_ajaran" class="form-control form-control-sm" onchange="document.getElementById('filterForm').submit();" style="width: 180px; border-radius: 6px;">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover m-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Dokumen</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $dokumen)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle font-weight-bold">{{ $dokumen->template->nama_perangkat ?? 'Template tidak ditemukan' }}</td>
                            <td class="align-middle">{{ $dokumen->mapel->nama_mapel ?? '-' }}</td>
                            <td class="align-middle">{{ $dokumen->kelas->nama_kelas ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @php
                                    $badgeClass = 'secondary';
                                    $statusText = 'Belum Dibuat';
                                    if ($dokumen->status == 'submitted') {
                                        $badgeClass = 'info';
                                        $statusText = 'Dikirim';
                                    } elseif ($dokumen->status == 'approved') {
                                        $badgeClass = 'success';
                                        $statusText = 'Disetujui';
                                    } elseif ($dokumen->status == 'rejected') {
                                        $badgeClass = 'danger';
                                        $statusText = 'Ditolak';
                                    } elseif ($dokumen->status == 'draft') {
                                        $badgeClass = 'warning';
                                        $statusText = 'Diproses (Draft)';
                                    }
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} px-3 py-2" style="font-size: 0.85rem; border-radius: 6px;">{{ $statusText }}</span>
                            </td>
                            <td class="text-center align-middle">
                                @if($dokumen->id)
                                    <a href="{{ route('guru.perangkat.print', $dokumen->id) }}" target="_blank" class="btn btn-sm btn-primary font-weight-bold" style="border-radius: 6px;">
                                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary font-weight-bold" style="border-radius: 6px;" disabled>
                                        <i class="fas fa-eye-slash mr-1"></i> Belum Ada
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                <h5>Belum ada dokumen administrasi</h5>
                                <p>Guru ini belum mengunggah atau membuat dokumen administrasi apapun.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card card-info card-outline mt-4 shadow-sm">
    <div class="card-header border-0">
        <h3 class="card-title font-weight-bold"><i class="fas fa-running mr-2 text-info"></i> Riwayat Kegiatan Guru</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover m-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Judul Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Periode</th>
                        <th class="text-center">Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatans as $kegiatan)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle"><strong>{{ $kegiatan->judul }}</strong></td>
                            <td class="align-middle text-muted">{{ \Str::limit($kegiatan->deskripsi, 60) }}</td>
                            <td class="align-middle">
                                {{ $kegiatan->tahun_ajaran }} <br>
                                <small class="text-muted">Semester {{ ucfirst($kegiatan->semester) }}</small>
                            </td>
                            <td class="text-center align-middle">
                                @if($kegiatan->foto_kegiatan)
                                    <a href="{{ asset('storage/' . $kegiatan->foto_kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold" style="border-radius: 6px;" title="Lihat Foto Kegiatan">
                                        <i class="fas fa-image mr-1"></i> Foto
                                    </a>
                                @endif
                                @if($kegiatan->sertifikat)
                                    <a href="{{ asset('storage/' . $kegiatan->sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold ml-1" style="border-radius: 6px;" title="Lihat Sertifikat">
                                        <i class="fas fa-certificate mr-1"></i> Sertifikat
                                    </a>
                                @endif
                                @if(!$kegiatan->foto_kegiatan && !$kegiatan->sertifikat)
                                    <span class="badge badge-light text-muted px-2 py-1 border">Tidak ada lampiran</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                                <h5>Belum ada kegiatan</h5>
                                <p>Guru ini belum menginputkan riwayat kegiatannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #495057;
        letter-spacing: 0.5px;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.2s ease;
    }
    .align-middle {
        vertical-align: middle !important;
    }
</style>
@stop
