@extends('adminlte::page')

@section('title', 'Setting Indikator PKG')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-cogs mr-2 text-primary"></i> Indikator Penilaian Kinerja Guru
        </h1>
    </div>
@stop

@section('content')

@php
$aspekData = [
    1 => [
        'title' => 'Perencanaan Pembelajaran',
        'desc'  => 'Formulir penilaian kinerja guru untuk Perencanaan Pembelajaran'
    ],
    2 => [
        'title' => 'Pelaksanaan Pembelajaran',
        'desc'  => 'Formulir penilaian kinerja guru untuk Pelaksanaan Pembelajaran'
    ],
    3 => [
        'title' => 'Membuka dan Menutup Pembelajaran',
        'desc'  => 'Formulir penilaian kinerja guru untuk Membuka dan Menutup Pembelajaran'
    ],
    4 => [
        'title' => 'Pelaksanaan Variasi Stimulus Pembelajaran',
        'desc'  => 'Formulir penilaian kinerja guru untuk Pelaksanaan Variasi Stimulus Pembelajaran'
    ],
    5 => [
        'title' => 'Pelaksanaan Keterampilan Bertanya',
        'desc'  => 'Formulir penilaian kinerja guru untuk Pelaksanaan Keterampilan Bertanya'
    ],
    6 => [
        'title' => 'Pelaksanaan Memberikan Penguatan',
        'desc'  => 'Formulir penilaian kinerja guru untuk Pelaksanaan Memberikan Penguatan'
    ],
    7 => [
        'title' => 'Pelaksanaan Menguatkan Kesimpulan Peserta Didik',
        'desc'  => 'Formulir penilaian kinerja guru untuk Pelaksanaan Menguatkan Kesimpulan Peserta Didik'
    ],
];
@endphp

<div class="container-fluid pb-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="aspekTabs" role="tablist">
                @for($i = 1; $i <= 7; $i++)
                    <li class="nav-item">
                        <a class="nav-link {{ $i == 1 ? 'active' : '' }}" href="#aspek-{{ $i }}" data-toggle="tab" role="tab">
                            <span class="d-none d-md-inline">Aspek</span> {{ $i }}
                        </a>
                    </li>
                @endfor
            </ul>
        </div>
        <div class="card-body bg-light">
            <div class="tab-content">
                @for($i = 1; $i <= 7; $i++)
                    @php
                        $kategoris = isset($groupedKategoris[$i]) ? $groupedKategoris[$i] : collect();
                    @endphp
                    <div class="tab-pane fade {{ $i == 1 ? 'show active' : '' }}" id="aspek-{{ $i }}" role="tabpanel">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="m-0 font-weight-bold text-secondary">
                                    <i class="fas fa-list-ul mr-2"></i> {{ $aspekData[$i]['title'] }}
                                </h5>
                                <p class="text-muted small mt-1 mb-0">{{ $aspekData[$i]['desc'] }}</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddKategori-{{ $i }}">
                                <i class="fas fa-plus mr-1"></i> Tambah Kategori
                            </button>
                        </div>

                        <!-- Modal Add Kategori -->
                        <div class="modal fade" id="modalAddKategori-{{ $i }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('kepala.pkg_settings.kategori.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Tambah Kategori Aspek {{ $i }}</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="aspek" value="{{ $i }}">
                                            <div class="form-group">
                                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nama" placeholder="Masukkan judul kategori..." required>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if($kategoris->count() > 0)
                            <div class="accordion" id="accordion-aspek-{{ $i }}">
                                @foreach($kategoris as $kategori)
                                    <div class="card card-outline card-secondary mb-3 shadow-sm" style="border-top-width: 2px;">
                                        <div class="card-header d-flex justify-content-between align-items-center bg-white p-3" id="heading-kat-{{ $kategori->id }}">
                                            <div class="d-flex align-items-center w-100 cursor-pointer" data-toggle="collapse" data-target="#collapse-kat-{{ $kategori->id }}" aria-expanded="true" aria-controls="collapse-kat-{{ $kategori->id }}">
                                                <h5 class="mb-0 text-dark font-weight-bold">
                                                    {{ $kategori->nama }}
                                                </h5>
                                                <span class="badge badge-info ml-3 badge-pill px-2 py-1">
                                                    {{ $kategori->indikators->count() }} Indikator
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex flex-shrink-0 ml-3">
                                                <button class="btn btn-sm btn-outline-primary mr-2" data-toggle="modal" data-target="#modalEditKategori-{{ $kategori->id }}" title="Edit Kategori">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('kepala.pkg_settings.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Kategori" onclick="event.preventDefault(); Swal.fire({title: 'Peringatan!', text: 'Menghapus kategori ini juga akan menghapus SEMUA indikator di dalamnya. Lanjutkan?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                        <i class="fas fa-trash"></i> Hapus Kategori
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Edit Kategori -->
                                        <div class="modal fade" id="modalEditKategori-{{ $kategori->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('kepala.pkg_settings.kategori.update', $kategori->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Edit Kategori</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="nama" value="{{ $kategori->nama }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="collapse-kat-{{ $kategori->id }}" class="collapse show" aria-labelledby="heading-kat-{{ $kategori->id }}" data-parent="#accordion-aspek-{{ $i }}">
                                            <div class="card-body p-0">
                                                
                                                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                                    <span class="text-muted text-sm"><i class="fas fa-info-circle"></i> Daftar pertanyaan/indikator untuk kategori ini.</span>
                                                    <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddIndikator-{{ $kategori->id }}">
                                                        <i class="fas fa-plus mr-1"></i> Tambah Indikator
                                                    </button>
                                                </div>

                                                <!-- Modal Add Indikator -->
                                                <div class="modal fade" id="modalAddIndikator-{{ $kategori->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow">
                                                            <form action="{{ route('kepala.pkg_settings.indikator.store') }}" method="POST">
                                                                @csrf
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Tambah Indikator</h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
                                                                    <div class="form-group">
                                                                        <label>Nama Indikator <span class="text-danger">*</span></label>
                                                                        <textarea class="form-control" name="nama" rows="3" placeholder="Masukkan deskripsi indikator/pertanyaan..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($kategori->indikators->count() > 0)
                                                    <ul class="list-group list-group-flush">
                                                        @foreach($kategori->indikators as $index => $indikator)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
                                                                <div class="d-flex align-items-start">
                                                                    <span class="badge badge-secondary mr-3 mt-1">{{ $index + 1 }}</span>
                                                                    <span>{{ $indikator->nama }}</span>
                                                                </div>
                                                                <div class="d-flex flex-shrink-0 ml-3">
                                                                    <button class="btn btn-sm btn-outline-info mr-2" data-toggle="modal" data-target="#modalEditIndikator-{{ $indikator->id }}" title="Edit Indikator">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <form action="{{ route('kepala.pkg_settings.indikator.destroy', $indikator->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Indikator" onclick="event.preventDefault(); Swal.fire({title: 'Yakin ingin menghapus indikator ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </li>

                                                            <!-- Modal Edit Indikator -->
                                                            <div class="modal fade" id="modalEditIndikator-{{ $indikator->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content border-0 shadow">
                                                                        <form action="{{ route('kepala.pkg_settings.indikator.update', $indikator->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="modal-header bg-info text-white">
                                                                                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Edit Indikator</h5>
                                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label>Nama Indikator <span class="text-danger">*</span></label>
                                                                                    <textarea class="form-control" name="nama" rows="3" required>{{ $indikator->nama }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer bg-light">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                                <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="p-4 text-center text-muted">
                                                        <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                                                        <p class="mb-0">Belum ada indikator untuk kategori ini.<br>Silakan tambahkan indikator baru.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
                                <h5 class="text-muted">Belum ada kategori untuk Aspek {{ $i }}</h5>
                                <p class="text-muted">Silakan klik tombol "Tambah Kategori" di atas untuk memulai.</p>
                            </div>
                        @endif

                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }
    .accordion .card-header[data-toggle="collapse"]:hover {
        background-color: #f4f6f9 !important;
    }
</style>
@endpush
@stop
