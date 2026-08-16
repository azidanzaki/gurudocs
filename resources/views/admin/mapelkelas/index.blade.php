@extends('adminlte::page')

@section('title', __('Mata Pelajaran & Kelas'))

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="font-weight-bold text-dark">Mata Pelajaran & Kelas</h1>
        <p class="text-muted mb-0">Kelola daftar mata pelajaran, kelas, dan penugasan guru.</p>
    </div>
    <div class="form-inline mt-3 mt-md-0 d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
        <label for="tahun_ajaran" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
        <select id="tahun_ajaran_selector" class="form-control mr-3 border-0 bg-light" style="border-radius: 8px; font-weight: bold;" onchange="changeTahunAjaran(this.value)">
            @foreach($tahunAjarans as $ta)
                <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>{{ $ta->nama }}</option>
            @endforeach
        </select>
        @if($nextTahunAjaran)
        <form action="{{ route('admin.kelolaperangkat.storeTahunAjaran') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="nama" value="{{ $nextTahunAjaran }}">
            <input type="hidden" name="redirect_to" value="admin.mapelkelas.index">
            <button type="button" class="btn btn-primary px-3 shadow-sm" style="border-radius: 8px;" onclick="Swal.fire({title: 'Tambah Tahun Ajaran?', text: 'Tambahkan tahun ajaran {{ $nextTahunAjaran }}?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, tambahkan!', cancelButtonText: 'Batal'}).then((result) => { if(result.isConfirmed) this.closest('form').submit(); })" title="Tambah Tahun Ajaran Baru">
                <i class="fas fa-plus mr-1"></i> Tambah TA ({{ $nextTahunAjaran }})
            </button>
        </form>
        @endif
    </div>
</div>
@if(!$isLatestYear)
<div class="alert alert-warning mt-3 shadow-sm border-0" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
    <i class="fas fa-exclamation-triangle mr-1"></i> Anda sedang melihat data historis untuk Tahun Ajaran <strong>{{ $selectedTahun }}</strong>. Data pada tahun ini tidak dapat diubah (Read-Only).
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

<style>
.nav-pills-custom .nav-link {
    color: #6c757d;
    font-weight: 600;
    border-radius: 50px;
    padding: 8px 20px;
    margin-right: 10px;
    transition: all 0.2s;
}
.nav-pills-custom .nav-link.active {
    background-color: #007bff;
    color: #fff;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}
.nav-pills-custom .nav-link:hover:not(.active) {
    background-color: #e9ecef;
}
</style>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <ul class="nav nav-pills nav-pills-custom" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'mapel' ? 'active' : '' }}" id="tab-mapel-tab" data-toggle="pill"
                    href="#tab-mapel" role="tab" aria-controls="tab-mapel"
                    aria-selected="{{ $activeTab == 'mapel' ? 'true' : 'false' }}"><i class="fas fa-book mr-1"></i> Mata Pelajaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'kelas' ? 'active' : '' }}" id="tab-kelas-tab" data-toggle="pill"
                    href="#tab-kelas" role="tab" aria-controls="tab-kelas"
                    aria-selected="{{ $activeTab == 'kelas' ? 'true' : 'false' }}"><i class="fas fa-chalkboard mr-1"></i> Kelas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'guru' ? 'active' : '' }}" id="tab-guru-tab" data-toggle="pill"
                    href="#tab-guru" role="tab" aria-controls="tab-guru"
                    aria-selected="{{ $activeTab == 'guru' ? 'true' : 'false' }}"><i class="fas fa-chalkboard-teacher mr-1"></i> Penugasan Guru</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-four-tabContent">

            {{-- TAB MATA PELAJARAN --}}
            <div class="tab-pane fade {{ $activeTab == 'mapel' ? 'show active' : '' }}" id="tab-mapel" role="tabpanel"
                aria-labelledby="tab-mapel-tab">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <div class="w-100 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <form action="{{ route('admin.mapelkelas.index') }}" method="GET" class="mb-0" id="form-search-mapel" style="flex: 1; max-width: 400px;">
                            <input type="hidden" name="tab" value="mapel">
                            <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                            <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="search_mapel" class="form-control border-left-0 bg-light"
                                    placeholder="Cari mata pelajaran..." value="{{ request('search_mapel') }}">
                                @if(request('search_mapel'))
                                <div class="input-group-append">
                                    <a href="{{ route('admin.mapelkelas.index') }}?tab=mapel&tahun_ajaran={{ $selectedTahun }}"
                                        class="btn btn-light border-left">
                                        <i class="fas fa-times text-muted"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </form>
                        
                        @if($isLatestYear)
                        <button class="btn btn-primary px-4 shadow-sm ml-auto mt-3 mt-md-0" style="border-radius: 8px;" data-toggle="modal" data-target="#modalTambahMapel">
                            <i class="fas fa-plus mr-2"></i> Tambah Mapel
                        </button>
                        @endif
                    </div>
                </div>

                <div class="table-responsive mt-4" id="table-container-mapel">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3" width="5%">No</th>
                                <th class="border-0 py-3">Nama Mata Pelajaran</th>
                                @if($isLatestYear)
                                <th class="border-0 py-3 text-center" width="20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapels as $mapel)
                                <tr>
                                    <td class="px-4 py-3 align-middle">{{ $mapels->firstItem() + $loop->index }}</td>
                                    <td class="py-3 align-middle font-weight-bold text-dark">{{ $mapel->nama_mapel }}</td>
                                     @if($isLatestYear)
                                     <td class="py-3 align-middle text-center">
                                        <div class="d-flex justify-content-center gap-2" style="gap: 8px;">
                                            <button class="btn btn-outline-info btn-sm px-3" style="border-radius: 6px;" data-toggle="modal"
                                                data-target="#modalEditMapel{{ $mapel->id }}"><i class="fas fa-edit"></i>
                                                Edit</button>
                                            <form action="{{ route('admin.mapelkelas.mapel.destroy', $mapel->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 6px;" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Mata Pelajaran?', text: 'Yakin ingin menghapus mata pelajaran ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"><i class="fas fa-trash"></i>
                                                    Hapus</button>
                                            </form>
                                        </div>
                                        {{-- Modal Edit Mapel --}}
                                        <div class="modal fade" id="modalEditMapel{{ $mapel->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog text-left modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                                    <form action="{{ route('admin.mapelkelas.mapel.update', $mapel->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-info text-white border-0 py-3">
                                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Mata Pelajaran</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                                aria-label="Close" style="opacity: 0.8;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-4 bg-light">
                                                            <div class="form-group mb-0">
                                                                <label class="font-weight-bold text-dark">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama_mapel" class="form-control" style="border-radius: 8px;"
                                                                    value="{{ $mapel->nama_mapel }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                                                            <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-info px-4 shadow-sm" style="border-radius: 8px;">Simpan Perubahan</button>
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
                                    <td colspan="{{ $isLatestYear ? 3 : 2 }}" class="text-center py-5 text-muted">
                                        <i class="fas fa-book-open fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Belum ada data mata pelajaran ditemukan.</p>
                                    </td>
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
                
                <div class="alert alert-light border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 4px solid #17a2b8 !important;">
                    <i class="fas fa-info-circle mr-2 text-info"></i> Daftar kelas ini akan digunakan untuk penugasan dan pengelompokan.
                </div>

                <div class="row">
                    @foreach($kelasGroups as $groupName => $kelasItems)
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                                    <h5 class="card-title font-weight-bold mb-0 text-dark">
                                        <i class="fas fa-layer-group text-info mr-2"></i> {{ $groupName }}
                                    </h5>

                                    @if(in_array($groupName, ['Kelas VII', 'Kelas VIII', 'Kelas IX']) && $isLatestYear)
                                        @php $prefix = str_replace('Kelas ', '', $groupName); @endphp

                                        <form action="{{ route('admin.mapelkelas.kelas.store') }}" method="POST"
                                            class="ml-auto mb-0">
                                            @csrf
                                            <input type="hidden" name="prefix" value="{{ $prefix }}">

                                            <button type="submit" class="btn btn-sm btn-outline-primary px-3 shadow-sm" style="border-radius: 6px;">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0 px-4 py-2 text-muted">Nama Kelas</th>
                                                @if($isLatestYear)
                                                <th class="border-0 px-4 py-2 text-center text-muted" width="100">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kelasItems as $k)
                                                <tr>
                                                    <td class="align-middle px-4 py-3 font-weight-bold text-dark">{{ $k->nama_kelas }}</td>
                                                     @if($isLatestYear)
                                                     <td class="text-center align-middle py-3">
                                                        <form action="{{ route('admin.mapelkelas.kelas.destroy', $k->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 6px; padding: 2px 8px; font-size: 12px;" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Kelas?', text: 'Yakin ingin menghapus kelas ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })" title="Hapus"><i class="fas fa-times"></i></button>
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
                    <form action="{{ route('admin.mapelkelas.index') }}" method="GET" class="mb-0 w-100" id="form-search-guru" style="max-width: 400px;">
                        <input type="hidden" name="tab" value="guru">
                        <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                        <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" name="search_guru" class="form-control border-left-0 bg-light"
                                placeholder="Cari nama guru atau NIP..." value="{{ request('search_guru') }}">
                            @if(request('search_guru'))
                            <div class="input-group-append">
                                <a href="{{ route('admin.mapelkelas.index') }}?tab=guru&tahun_ajaran={{ $selectedTahun }}"
                                    class="btn btn-light border-left">
                                    <i class="fas fa-times text-muted"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="table-responsive mt-4" id="table-container-guru">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3" width="5%">No</th>
                                <th class="border-0 py-3">Nama Guru</th>
                                <th class="border-0 py-3">NIP</th>
                                <th class="border-0 py-3">Mata Pelajaran</th>
                                <th class="border-0 py-3">Kelas</th>
                                <th class="border-0 py-3 text-center" width="20%">Penugasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $guru)
                                <tr>
                                    <td class="px-4 py-3 align-middle">{{ $gurus->firstItem() + $loop->index }}</td>
                                    <td class="py-3 align-middle font-weight-bold text-dark">{{ $guru->name }}</td>
                                    <td class="py-3 align-middle">{{ $guru->nip }}</td>
                                    <td class="py-3 align-middle">
                                        @if($guru->mapels->count() > 0)
                                            <div class="d-flex flex-wrap gap-1" style="gap: 4px;">
                                                @foreach($guru->mapels as $m)
                                                    <span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 4px;">{{ $m->nama_mapel }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted font-italic small">Belum ada mapel</span>
                                        @endif
                                    </td>
                                    <td class="py-3 align-middle">
                                        @if($guru->kelas->count() > 0)
                                            <div class="d-flex flex-wrap gap-1" style="gap: 4px;">
                                                @foreach($guru->kelas as $k)
                                                    <span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 4px;">{{ $k->nama_kelas }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted font-italic small">Belum ada kelas</span>
                                        @endif
                                    </td>
                                     <td class="py-3 align-middle text-center">
                                        <button class="btn btn-sm btn-outline-primary px-3" style="border-radius: 6px;" data-toggle="modal"
                                            data-target="#modalPenugasan{{ $guru->id }}">
                                            @if($isLatestYear)
                                            <i class="fas fa-tasks mr-1"></i> Kelola
                                            @else
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                            @endif
                                        </button>

                                        {{-- Modal Kelola Penugasan --}}
                                        <div class="modal fade" id="modalPenugasan{{ $guru->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg text-left modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                                    <div class="modal-header bg-primary text-white border-0 py-3">
                                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-tasks mr-2"></i> Kelola Penugasan: {{ $guru->name }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close" style="opacity: 0.8;">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-4 bg-light">

                                                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-list-ul mr-2"></i>Daftar Penugasan Saat Ini</h6>
                                                        <div class="table-responsive shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #eaeaea;">
                                                            <table class="table table-hover mb-0">
                                                                <thead class="bg-white">
                                                                    <tr>
                                                                        <th class="border-0 text-muted">Mata Pelajaran</th>
                                                                        <th class="border-0 text-muted">Kelas</th>
                                                                        <th width="100" class="border-0 text-center text-muted">Aksi</th>
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
                                                                            <td class="align-middle font-weight-bold text-dark">{{ $p->nama_mapel }}</td>
                                                                            <td class="align-middle"><span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 4px;">{{ $p->nama_kelas }}</span></td>
                                                                             <td class="align-middle text-center">
                                                                                @if($isLatestYear)
                                                                                <form
                                                                                    action="{{ route('admin.mapelkelas.penugasan.destroy', $p->id) }}"
                                                                                    method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="button"
                                                                                        class="btn btn-sm btn-outline-danger" style="border-radius: 6px; padding: 2px 8px; font-size: 12px;" onclick="event.preventDefault(); Swal.fire({title: 'Hapus Penugasan?', text: 'Hapus penugasan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })" title="Hapus"><i class="fas fa-times"></i></button>
                                                                                </form>
                                                                                @else
                                                                                <span class="text-muted">-</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="3" class="text-center py-4 text-muted">
                                                                                <i class="fas fa-info-circle mb-2 d-block"></i>
                                                                                Belum ada penugasan
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                         @if($isLatestYear)
                                                        <hr class="my-4" style="border-top: 2px dashed #eaeaea;">

                                                        <h6 class="font-weight-bold text-success mb-2"><i class="fas fa-plus-circle mr-2"></i>Tambah Penugasan Baru</h6>
                                                        <p class="text-muted small mb-3">Pilih mata pelajaran beserta kelas-kelas yang diajar.</p>

                                                        <form
                                                            action="{{ route('admin.mapelkelas.penugasan.store', $guru->id) }}"
                                                            method="POST" class="bg-white p-3 shadow-sm border" style="border-radius: 12px;">
                                                            @csrf
                                                            <div id="dynamic-penugasan-container-{{ $guru->id }}">
                                                                <div class="row penugasan-row align-items-start mb-3">
                                                                    <div class="col-md-5">
                                                                        <div class="form-group mb-0">
                                                                            <label class="small font-weight-bold text-muted">Mata Pelajaran <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="penugasans[0][mapel_id]"
                                                                                class="form-control" style="border-radius: 8px;" required>
                                                                                <option value="">-- Pilih --</option>
                                                                                @foreach($allMapels as $m)
                                                                                    <option value="{{ $m->id }}">
                                                                                        {{ $m->nama_mapel }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-0">
                                                                            <label class="small font-weight-bold text-muted">Kelas <span class="text-danger">*</span></label>
                                                                            <select name="penugasans[0][kelas_ids][]"
                                                                                class="select2bs4 form-control" multiple="multiple"
                                                                                data-placeholder="Pilih kelas..."
                                                                                style="width: 100%; border-radius: 8px;" required>
                                                                                @foreach($allKelas as $k)
                                                                                    <option value="{{ $k->id }}">
                                                                                        {{ $k->nama_kelas }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 pl-0">
                                                                        <label class="small d-block mb-1">&nbsp;</label>
                                                                        <button type="button"
                                                                            class="btn btn-outline-danger btn-sm btn-remove-row w-100"
                                                                            style="display:none; border-radius: 8px; height: 38px;" title="Hapus Baris"><i
                                                                                class="fas fa-times"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #eaeaea;">
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm btn-add-row" style="border-radius: 6px;"
                                                                    data-guru="{{ $guru->id }}"><i class="fas fa-plus mr-1"></i>
                                                                    Baris Baru</button>
                                                                <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 8px;"><i
                                                                        class="fas fa-save mr-1"></i> Simpan Penugasan</button>
                                                            </div>
                                                        </form>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                                                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;"
                                                            data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-chalkboard-teacher fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Belum ada data guru ditemukan.</p>
                                    </td>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <form action="{{ route('admin.mapelkelas.mapel.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus mr-2"></i> Tambah Mata Pelajaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mapel" class="form-control" style="border-radius: 8px;" placeholder="Masukkan nama mapel" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;"><i class="fas fa-save mr-1"></i> Simpan Mapel</button>
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
