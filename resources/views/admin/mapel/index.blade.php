@extends('adminlte::page')

@section('title', __('Manajemen Mata Pelajaran'))

@section('content_header')
    <h1>{{ __('Manajemen Mata Pelajaran') }}</h1>
@stop

@section('content')

@if(session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ __(session('success')) }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <form action="{{ route('admin.mapel.index') }}" method="GET" class="form-inline">
                <input type="text"
                       name="search"
                       class="form-control mr-2"
                       placeholder="Cari mata pelajaran..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary ml-2">
                        Reset
                    </a>
                @endif
            </form>

            <button class="btn btn-success mt-2 mt-md-0"
                    data-toggle="modal"
                    data-target="#modalTambahMapel">
                <i class="fas fa-plus"></i>
                Tambah Mata Pelajaran
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>{{ __('Nama Mata Pelajaran') }}</th>
                    <th width="200" class="text-center">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapels as $mapel)
                    <tr>
                        <td>{{ $mapels->firstItem() + $loop->index }}</td>
                        <td>{{ $mapel->nama_mapel }}</td>
                        <td class="project-actions text-center">
                            <!-- EDIT -->
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEdit{{ $mapel->id }}">
                                <i class="fas fa-pencil-alt"></i> {{ __('Edit') }}
                            </button>
                            
                            <!-- DELETE -->
                            <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); Swal.fire({title: '{{ __('Hapus mata pelajaran ini?') }}', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                    <i class="fas fa-trash"></i> {{ __('Hapus') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ __('Belum ada data mata pelajaran') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3 d-flex justify-content-center">
        {{ $mapels->links('pagination::bootstrap-4') }}
    </div> 
</div>

<!-- MODAL TAMBAH MAPEL -->
<div class="modal fade" id="modalTambahMapel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">{{ __('Tambah Mata Pelajaran') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mapel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Nama Mata Pelajaran') }}</label>
                        <input type="text" name="nama_mapel" class="form-control" required placeholder="Contoh: Matematika">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT MAPEL -->
@foreach($mapels as $mapel)
<div class="modal fade" id="modalEdit{{ $mapel->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">{{ __('Edit Mata Pelajaran') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Nama Mata Pelajaran') }}</label>
                        <input type="text" name="nama_mapel" class="form-control" value="{{ $mapel->nama_mapel }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> {{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@stop
