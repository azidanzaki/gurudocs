@extends('adminlte::page')

@section('title', __('Mata Pelajaran & Kelas'))

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <h1>{{ __('Mapelkelas: Mata Pelajaran, Kelas & Guru') }}</h1>
    <div class="form-inline mt-2 mt-md-0 d-flex align-items-center">
        <label for="tahun_ajaran" class="mr-2">Tahun Ajaran:</label>
        <select id="tahun_ajaran_selector" class="form-control mr-2" onchange="changeTahunAjaran(this.value)">
            @foreach($tahunAjarans as $ta)
                <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>{{ $ta->nama }}</option>
            @endforeach
        </select>
        @if($nextTahunAjaran)
        <form action="{{ route('admin.kelolaperangkat.storeTahunAjaran') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="nama" value="{{ $nextTahunAjaran }}">
            <input type="hidden" name="redirect_to" value="admin.mapelkelas.index">
            <button type="button" class="btn btn-success" onclick="Swal.fire({title: 'Tambah Tahun Ajaran?', text: 'Tambahkan tahun ajaran {{ $nextTahunAjaran }}?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, tambahkan!', cancelButtonText: 'Batal'}).then((result) => { if(result.isConfirmed) this.closest('form').submit(); })" title="Tambah Tahun Ajaran Baru">
                <i class="fas fa-plus"></i>
            </button>
        </form>
        @endif
    </div>
</div>
@if(!$isLatestYear)
<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle"></i> Data Tahun Ajaran {{ $selectedTahun }}.
</div>
@endif
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ __(session('success')) }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ __(session('error')) }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@php
    $activeTab = request('tab', session('tab', 'mapel'));

    // Grouping kelas
    $kelasVII = $kelas->filter(fn($k) => str_starts_with($k->nama_kelas, 'VII') && !str_starts_with($k->nama_kelas, 'VIII'));
    $kelasVIII = $kelas->filter(fn($k) => str_starts_with($k->nama_kelas, 'VIII'));
    $kelasIX = $kelas->filter(fn($k) => str_starts_with($k->nama_kelas, 'IX'));
    $kelasLain = $kelas->diff($kelasVII)->diff($kelasVIII)->diff($kelasIX);

    $kelasGroups = [
        'Kelas VII' => $kelasVII,
        'Kelas VIII' => $kelasVIII,
        'Kelas IX' => $kelasIX,
    ];
    if ($kelasLain->count() > 0) {
        $kelasGroups['Kelas Lainnya'] = $kelasLain;
    }
@endphp

<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'mapel' ? 'active' : '' }}" id="tab-mapel-tab" data-toggle="pill"
                    href="#tab-mapel" role="tab" aria-controls="tab-mapel"
                    aria-selected="{{ $activeTab == 'mapel' ? 'true' : 'false' }}">Mata Pelajaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'kelas' ? 'active' : '' }}" id="tab-kelas-tab" data-toggle="pill"
                    href="#tab-kelas" role="tab" aria-controls="tab-kelas"
                    aria-selected="{{ $activeTab == 'kelas' ? 'true' : 'false' }}">Kelas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'guru' ? 'active' : '' }}" id="tab-guru-tab" data-toggle="pill"
                    href="#tab-guru" role="tab" aria-controls="tab-guru"
                    aria-selected="{{ $activeTab == 'guru' ? 'true' : 'false' }}">Guru</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-four-tabContent">

            {{-- TAB MATA PELAJARAN --}}
            <div class="tab-pane fade {{ $activeTab == 'mapel' ? 'show active' : '' }}" id="tab-mapel" role="tabpanel"
                aria-labelledby="tab-mapel-tab">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <form action="{{ route('admin.mapelkelas.index') }}" method="GET" class="form-inline mt-2" id="form-search-mapel">
                        <input type="hidden" name="tab" value="mapel">
                        <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                        <div class="input-group">
                            <input type="text" name="search_mapel" class="form-control auto-search"
                                placeholder="Cari mata pelajaran..." value="{{ request('search_mapel') }}">
                            @if(request('search_mapel'))
                            <div class="input-group-append">
                                <a href="{{ route('admin.mapelkelas.index') }}?tab=mapel&tahun_ajaran={{ $selectedTahun }}"
                                    class="btn btn-secondary">Reset</a>
                            </div>
                            @endif
                        </div>
                    </form>
                    @if($isLatestYear)
                    <button class="btn btn-success mt-2" data-toggle="modal" data-target="#modalTambahMapel">
                        <i class="fas fa-plus"></i> Tambah Mata Pelajaran
                    </button>
                    @endif
                </div>

                <div class="table-responsive" id="table-container-mapel">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-light">
                                <th width="50">No</th>
                                <th>Nama Mata Pelajaran</th>
                                @if($isLatestYear)
                                <th width="200" class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapels as $mapel)
                                <tr>
                                    <td>{{ $mapels->firstItem() + $loop->index }}</td>
                                    <td>{{ $mapel->nama_mapel }}</td>
                                     @if($isLatestYear)
                                     <td class="text-center">
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#modalEditMapel{{ $mapel->id }}"><i class="fas fa-edit"></i>
                                            Edit</button>
                                        <form action="{{ route('admin.mapelkelas.mapel.destroy', $mapel->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Mata Pelajaran?', text: 'Yakin ingin menghapus mata pelajaran ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"><i class="fas fa-trash"></i>
                                                Hapus</button>
                                        </form>
                                        {{-- Modal Edit Mapel --}}
                                        <div class="modal fade" id="modalEditMapel{{ $mapel->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog text-left">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.mapelkelas.mapel.update', $mapel->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nama Mata Pelajaran</label>
                                                                <input type="text" name="nama_mapel" class="form-control"
                                                                    value="{{ $mapel->nama_mapel }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isLatestYear ? 3 : 2 }}" class="text-center">Belum ada data mata pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center" id="pagination-container-mapel">
                    {{ $mapels->appends(request()->except('mapel_page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>

            {{-- TAB KELAS --}}
            <div class="tab-pane fade {{ $activeTab == 'kelas' ? 'show active' : '' }}" id="tab-kelas" role="tabpanel"
                aria-labelledby="tab-kelas-tab">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <!-- Pencarian Kelas dinonaktifkan -->
                </div>

                <div class="row">
                    @foreach($kelasGroups as $groupName => $kelasItems)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-info text-white d-flex align-items-center">
                                    <h5 class="card-title mb-0">{{ $groupName }}</h5>

                                    @if(in_array($groupName, ['Kelas VII', 'Kelas VIII', 'Kelas IX']) && $isLatestYear)
                                        @php $prefix = str_replace('Kelas ', '', $groupName); @endphp

                                        <form action="{{ route('admin.mapelkelas.kelas.store') }}" method="POST"
                                            class="ml-auto mb-0">
                                            @csrf
                                            <input type="hidden" name="prefix" value="{{ $prefix }}">

                                            <button type="submit" class="btn btn-sm btn-light text-info">
                                                <i class="fas fa-plus mr-1"></i> Tambah Kelas
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama Kelas</th>
                                                @if($isLatestYear)
                                                <th width="120" class="text-center">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kelasItems as $k)
                                                <tr>
                                                    <td class="align-middle">{{ $k->nama_kelas }}</td>
                                                     @if($isLatestYear)
                                                     <td class="text-center align-middle">
                                                        <form action="{{ route('admin.mapelkelas.kelas.destroy', $k->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Kelas?', text: 'Yakin ingin menghapus kelas ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"><i
                                                                    class="fas fa-trash"></i>Hapus</button>
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ $isLatestYear ? 2 : 1 }}" class="text-center text-muted">Belum ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB GURU --}}
            <div class="tab-pane fade {{ $activeTab == 'guru' ? 'show active' : '' }}" id="tab-guru" role="tabpanel"
                aria-labelledby="tab-guru-tab">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <form action="{{ route('admin.mapelkelas.index') }}" method="GET" class="form-inline mt-2" id="form-search-guru">
                        <input type="hidden" name="tab" value="guru">
                        <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                        <div class="input-group">
                            <input type="text" name="search_guru" class="form-control auto-search"
                                placeholder="Cari nama atau NIP..." value="{{ request('search_guru') }}">
                            @if(request('search_guru'))
                            <div class="input-group-append">
                                <a href="{{ route('admin.mapelkelas.index') }}?tab=guru&tahun_ajaran={{ $selectedTahun }}"
                                    class="btn btn-secondary">Reset</a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="table-responsive" id="table-container-guru">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-light">
                                <th width="50">No</th>
                                <th>Nama Guru</th>
                                <th>NIP</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $guru)
                                <tr>
                                    <td>{{ $gurus->firstItem() + $loop->index }}</td>
                                    <td>{{ $guru->name }}</td>
                                    <td>{{ $guru->nip }}</td>
                                    <td>
                                        @if($guru->mapels->count() > 0)
                                            <ul class="mb-0 pl-3">
                                                @foreach($guru->mapels as $m)
                                                    <li>{{ $m->nama_mapel }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guru->kelas->count() > 0)
                                            <ul class="mb-0 pl-3">
                                                @foreach($guru->kelas as $k)
                                                    <li>{{ $k->nama_kelas }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                     <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#modalPenugasan{{ $guru->id }}">
                                            @if($isLatestYear)
                                            <i class="fas fa-tasks"></i> Kelola Mapel dan Kelas
                                            @else
                                            <i class="fas fa-eye"></i> Lihat Penugasan
                                            @endif
                                        </button>

                                        {{-- Modal Kelola Penugasan --}}
                                        <div class="modal fade" id="modalPenugasan{{ $guru->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg text-left">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Kelola Penugasan: {{ $guru->name }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h6 class="font-weight-bold">Daftar Penugasan Saat Ini</h6>
                                                        <table class="table table-sm table-bordered mt-2 mb-4">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th>Mata Pelajaran</th>
                                                                    <th>Kelas</th>
                                                                    <th width="100" class="text-center">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                 @php
                                                                    $penugasans = \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')
                                                                        ->join('mapels', 'guru_mapel_kelas.mapel_id', '=', 'mapels.id')
                                                                        ->join('kelas', 'guru_mapel_kelas.kelas_id', '=', 'kelas.id')
                                                                        ->where('guru_mapel_kelas.user_id', $guru->id)
                                                                        ->where('guru_mapel_kelas.tahun_ajaran', $selectedTahun)
                                                                        ->select('guru_mapel_kelas.id', 'mapels.nama_mapel', 'kelas.nama_kelas')
                                                                        ->get();
                                                                @endphp
                                                                @forelse($penugasans as $p)
                                                                    <tr>
                                                                        <td>{{ $p->nama_mapel }}</td>
                                                                        <td>{{ $p->nama_kelas }}</td>
                                                                         <td class="text-center">
                                                                            @if($isLatestYear)
                                                                            <form
                                                                                action="{{ route('admin.mapelkelas.penugasan.destroy', $p->id) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="button"
                                                                                    class="btn btn-xs btn-danger" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Penugasan?', text: 'Hapus penugasan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"><i
                                                                                        class="fas fa-times"></i> Hapus</button>
                                                                            </form>
                                                                            @else
                                                                            <span class="text-muted">-</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center text-muted">Belum ada
                                                                            penugasan</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>

                                                         @if($isLatestYear)
                                                        <hr>

                                                        <h6 class="font-weight-bold">Tambah Penugasan Baru</h6>
                                                        <p class="text-muted small">Anda bisa menambahkan beberapa mata
                                                            pelajaran dan beberapa kelas sekaligus.</p>

                                                        <form
                                                            action="{{ route('admin.mapelkelas.penugasan.store', $guru->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div id="dynamic-penugasan-container-{{ $guru->id }}">
                                                                <div class="row penugasan-row mb-3 pb-3 border-bottom">
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label>Mata Pelajaran <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="penugasans[0][mapel_id]"
                                                                                class="form-control" required>
                                                                                <option value="">-- Pilih Mata Pelajaran --
                                                                                </option>
                                                                                @foreach($allMapels as $m)
                                                                                    <option value="{{ $m->id }}">
                                                                                        {{ $m->nama_mapel }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <label>Kelas (Bisa pilih lebih dari satu) <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="penugasans[0][kelas_ids][]"
                                                                                class="select2bs4" multiple="multiple"
                                                                                data-placeholder="Pilih kelas..."
                                                                                style="width: 100%;" required>
                                                                                @foreach($allKelas as $k)
                                                                                    <option value="{{ $k->id }}">
                                                                                        {{ $k->nama_kelas }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 d-flex align-items-center">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm btn-remove-row"
                                                                            style="display:none;" title="Hapus Baris"><i
                                                                                class="fas fa-trash"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm btn-add-row"
                                                                    data-guru="{{ $guru->id }}"><i class="fas fa-plus"></i>
                                                                    Tambah Mapel & Kelas Lain</button>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-success btn-block"><i
                                                                        class="fas fa-save"></i> Simpan</button>
                                                            </div>
                                                        </form>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center" id="pagination-container-guru">
                    {{ $gurus->appends(request()->except('guru_page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Tambah Mapel --}}
<div class="modal fade" id="modalTambahMapel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.mapelkelas.mapel.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



@stop

@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.0.4/select2-bootstrap4.min.css">
<style>
    /* Fix missing borders and sizing when Select2 is used in AdminLTE/Bootstrap 4 without form-control */
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        min-height: calc(2.25rem + 2px) !important;
    }

    /* Ensure multiple choice tags are sized properly and look like cards */
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #006fe6 !important;
        color: #fff !important;
        padding: 3px 8px !important;
        margin-top: 5px !important;
        margin-right: 5px !important;
        border-radius: 4px !important;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    /* Style the 'x' remove button on the tags */
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, .8) !important;
        margin-right: 5px !important;
        font-weight: bold;
    }

    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff !important;
    }

    /* Fix placeholder text getting cut off */
    .select2-container--bootstrap4 .select2-search--inline .select2-search__field {
        min-width: 150px !important;
    }

    /* Fix dropdown going behind modal or getting cut off */
    .select2-container--open {
        z-index: 1059 !important;
        /* higher than Bootstrap modal (1050) */
    }

    /* Make the dropdown menu scrollable */
    .select2-results__options {
        max-height: 200px !important;
        overflow-y: auto !important;
    }

    /* Show selected options in dropdown instead of hiding them */
    .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
        display: block !important;
        background-color: #f8f9fa !important;
        color: #6c757d !important;
    }

    /* Add a checkmark to selected options */
    .select2-container--bootstrap4 .select2-results__option[aria-selected="true"]::after {
        content: " ✓";
        float: right;
        color: #28a745;
        font-weight: bold;
    }

    /* Fix container width collapsing */
    .select2-container {
        width: 100% !important;
    }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    function changeTahunAjaran(value) {
        try {
            var currentTab = 'mapel';
            var activeLink = document.querySelector('.nav-tabs .nav-link.active');
            if (activeLink) {
                var href = activeLink.getAttribute('href');
                if (href) {
                    currentTab = href.replace('#tab-', '');
                }
            }
            
            var url = new URL(window.location.href);
            url.searchParams.set('tahun_ajaran', value);
            url.searchParams.set('tab', currentTab);
            
            // Delete mapel_page and guru_page from URL so it goes back to page 1 on year change
            url.searchParams.delete('mapel_page');
            url.searchParams.delete('guru_page');
            
            window.location.href = url.toString();
        } catch (e) {
            console.error('Error changing tahun ajaran:', e);
            // Fallback
            window.location.href = '?tahun_ajaran=' + encodeURIComponent(value) + '&tab=mapel';
        }
    }

    $(document).ready(function () {
        function initSelect2(context) {
            var ctx = context || $(document);
            ctx.find('.select2bs4').each(function () {
                var $this = $(this);
                $this.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $this.closest('.modal').length ? $this.closest('.modal') : $(document.body)
                });
            });
        }

        // Initial setup
        initSelect2();

        // Handle dynamic rows for Penugasan Guru
        $('.btn-add-row').click(function () {
            var guruId = $(this).data('guru');
            var container = $('#dynamic-penugasan-container-' + guruId);
            var newIndex = new Date().getTime(); // unique index

            // Get the first row to clone
            var firstRow = container.find('.penugasan-row').first();

            // Destroy select2 on the original element temporarily to cleanly clone it
            firstRow.find('.select2bs4').select2('destroy');

            var newRow = firstRow.clone();

            // Update names with new index
            newRow.find('select, input').each(function () {
                var name = $(this).attr('name');
                if (name) {
                    name = name.replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', name);
                }
                if ($(this).is('select')) {
                    $(this).prop('selectedIndex', 0); // reset select
                } else {
                    $(this).val(''); // reset input
                }
            });

            // Show remove button
            newRow.find('.btn-remove-row').show();

            // Append the new row
            container.append(newRow);

            // Re-initialize select2 on both the original and the new row
            initSelect2(firstRow);
            initSelect2(newRow);
        });

        // Handle remove row
        $(document).on('click', '.btn-remove-row', function () {
            $(this).closest('.penugasan-row').remove();
        });

        // Fix Select2 width when modal opens
        $('.modal').on('shown.bs.modal', function () {
            // Re-initialize Select2 inside the opened modal to fix 0px width bug
            $(this).find('.select2bs4').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
            initSelect2($(this));
        });

        // Update URL on tab change to persist active tab
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href").replace('#tab-', '');
            var url = new URL(window.location.href);
            url.searchParams.set('tab', target);
            window.history.replaceState({}, '', url);

            // Update all pagination links to include the current tab
            $('.pagination a').each(function() {
                var linkUrl = new URL(this.href);
                linkUrl.searchParams.set('tab', target);
                this.href = linkUrl.toString();
            });
        });

        // Real-time AJAX search with debounce
        let searchTimeout = null;
        $('.auto-search').on('input', function() {
            clearTimeout(searchTimeout);
            let form = $(this).closest('form');
            let tab = form.find('input[name="tab"]').val(); // 'mapel' or 'guru'
            searchTimeout = setTimeout(function() {
                let url = form.attr('action') + '?' + form.serialize();
                $.get(url, function(data) {
                    let newTable = $(data).find('#table-container-' + tab).html();
                    let newPagination = $(data).find('#pagination-container-' + tab).html();
                    $('#table-container-' + tab).html(newTable);
                    $('#pagination-container-' + tab).html(newPagination);
                });
            }, 500);
        });

        // Open modal guru if returning from delete assignment
        @if(session('open_modal_guru'))
            $('#modalPenugasan{{ session("open_modal_guru") }}').modal('show');
        @endif
    });
</script>
@stop
