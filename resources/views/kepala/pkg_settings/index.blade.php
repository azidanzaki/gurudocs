@extends('adminlte::page')

@section('title', 'Setting Indikator PKG')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
        <h1 class="m-0 text-dark font-weight-bold">
            Indikator Penilaian Kinerja Guru
        </h1>
        <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
            @if(!$isEditable)
                <span class="badge badge-warning px-3 py-2 mr-3 font-weight-bold shadow-sm" style="border-radius: 8px;"><i class="fas fa-lock mr-1"></i> Mode View-Only</span>
            @endif
            <label for="tahun_ajaran_id" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
            <form action="{{ route('kepala.pkg_settings.index') }}" method="GET" class="mb-0">
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



    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white p-0 border-bottom">
            <ul class="nav nav-tabs border-0" id="aspekTabs" role="tablist">
                @for($i = 1; $i <= 7; $i++)
                    <li class="nav-item">
                        <a class="nav-link border-0 text-secondary font-weight-bold py-3 px-4 {{ $i == 1 ? 'active text-primary' : '' }}" style="{{ $i == 1 ? 'border-bottom: 3px solid #007bff !important;' : '' }}" href="#aspek-{{ $i }}" data-toggle="tab" role="tab" onclick="updateTabStyles(this)">
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
                                <h5 class="m-0 font-weight-bold text-dark">
                                    {{ $aspekData[$i]['title'] }}
                                </h5>
                                <p class="text-muted small mt-1 mb-0">{{ $aspekData[$i]['desc'] }}</p>
                            </div>
                            @if($isEditable)
                            <button type="button" class="btn btn-primary shadow-sm px-4" style="border-radius: 8px;" data-toggle="modal" data-target="#modalAddKategori-{{ $i }}">
                                <i class="fas fa-plus mr-1"></i> Tambah Kategori
                            </button>
                            @endif
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
                                            <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunId }}">
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
                                    <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; border: 1px solid #eaeaea !important;">
                                        <div class="card-header d-flex justify-content-between align-items-center bg-white p-3 border-bottom-0" id="heading-kat-{{ $kategori->id }}">
                                            <div class="d-flex align-items-center w-100 cursor-pointer" data-toggle="collapse" data-target="#collapse-kat-{{ $kategori->id }}" aria-expanded="true" aria-controls="collapse-kat-{{ $kategori->id }}">
                                                <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-folder-open"></i>
                                                </div>
                                                <h5 class="mb-0 text-dark font-weight-bold" style="font-size: 1.1rem;">
                                                    {{ $kategori->nama }}
                                                </h5>
                                                <span class="badge badge-info ml-3 px-3 py-1 shadow-sm" style="border-radius: 6px;">
                                                    {{ $kategori->indikators->count() }} Indikator
                                                </span>
                                            </div>
                                            
                                            @if($isEditable)
                                            <div class="d-flex flex-shrink-0 ml-3">
                                                <button class="btn btn-sm btn-light border shadow-sm px-3 mr-2" style="border-radius: 6px;" data-toggle="modal" data-target="#modalEditKategori-{{ $kategori->id }}" title="Edit Kategori">
                                                    <i class="fas fa-edit text-info"></i>
                                                </button>
                                                <form action="{{ route('kepala.pkg_settings.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-light border shadow-sm px-3" style="border-radius: 6px;" title="Hapus Kategori" onclick="event.preventDefault(); Swal.fire({title: 'Peringatan!', text: 'Menghapus kategori ini juga akan menghapus SEMUA indikator di dalamnya. Lanjutkan?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
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
                                                            <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="collapse-kat-{{ $kategori->id }}" class="collapse show" aria-labelledby="heading-kat-{{ $kategori->id }}" data-parent="#accordion-aspek-{{ $i }}">
                                            <div class="card-body p-0">
                                                
                                                <div class="p-3 bg-light border-top border-bottom d-flex justify-content-between align-items-center">
                                                    <span class="text-muted text-sm font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Daftar indikator penilaian</span>
                                                    @if($isEditable)
                                                    <button class="btn btn-success btn-sm shadow-sm px-3" style="border-radius: 6px;" data-toggle="modal" data-target="#modalAddIndikator-{{ $kategori->id }}">
                                                        <i class="fas fa-plus mr-1"></i> Tambah Indikator
                                                    </button>
                                                    @endif
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
                                                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action py-3 border-0 border-bottom">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px; font-weight: bold;">
                                                                        {{ $index + 1 }}
                                                                    </div>
                                                                    <span class="text-dark" style="line-height: 1.6;">{{ $indikator->nama }}</span>
                                                                </div>
                                                                @if($isEditable)
                                                                <div class="d-flex flex-shrink-0 ml-4">
                                                                    <button class="btn btn-sm btn-light text-info border shadow-sm px-3 mr-2" style="border-radius: 6px;" data-toggle="modal" data-target="#modalEditIndikator-{{ $indikator->id }}" title="Edit Indikator">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <form action="{{ route('kepala.pkg_settings.indikator.destroy', $indikator->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="btn btn-sm btn-light text-danger border shadow-sm px-3" style="border-radius: 6px;" title="Hapus Indikator" onclick="event.preventDefault(); Swal.fire({title: 'Yakin ingin menghapus indikator ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                @endif
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
                                                                                <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i> Simpan</button>
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
    .nav-tabs .nav-link {
        color: #6c757d;
        border-bottom: 3px solid transparent !important;
        transition: all 0.2s;
    }
    .nav-tabs .nav-link:hover {
        color: #007bff;
        border-bottom: 3px solid #dee2e6 !important;
    }
    .nav-tabs .nav-link.active {
        color: #007bff !important;
        border-bottom: 3px solid #007bff !important;
        background-color: transparent !important;
    }
</style>
<script>
    function updateTabStyles(activeTab) {
        // Reset all tabs
        document.querySelectorAll('.nav-tabs .nav-link').forEach(function(tab) {
            tab.style.borderBottom = '3px solid transparent';
            tab.classList.remove('text-primary');
            tab.classList.add('text-secondary');
        });
        
        // Highlight active tab
        activeTab.style.borderBottom = '3px solid #007bff';
        activeTab.classList.remove('text-secondary');
        activeTab.classList.add('text-primary');
    }
</script>
@endpush
@stop
