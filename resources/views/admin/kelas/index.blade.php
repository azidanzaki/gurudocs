@extends('adminlte::page')

@section('title', __('Manajemen Kelas'))

@section('content_header')
    <h1>{{ __('Manajemen Kelas') }}</h1>
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
            <form action="{{ route('admin.kelas.index') }}" method="GET" class="form-inline">
                <input type="text"
                       name="search"
                       class="form-control mr-2"
                       placeholder="Cari kelas..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary ml-2">
                        Reset
                    </a>
                @endif
            </form>

            <button class="btn btn-success mt-2 mt-md-0"
                    data-toggle="modal"
                    data-target="#modalTambahKelas">
                <i class="fas fa-plus"></i>
                Tambah Kelas
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>{{ __('Nama Kelas') }}</th>
                    <th width="200" class="text-center">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                    <tr>
                        <td>{{ $kelas->firstItem() + $loop->index }}</td>
                        <td>{{ $k->nama_kelas }}</td>
                        <td class="project-actions text-center">
                            <!-- EDIT -->
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEdit{{ $k->id }}">
                                <i class="fas fa-pencil-alt"></i> {{ __('Edit') }}
                            </button>
                            
                            <!-- DELETE -->
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); Swal.fire({title: '{{ __('Hapus kelas ini?') }}', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                    <i class="fas fa-trash"></i> {{ __('Hapus') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            {{ __('Belum ada data kelas') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3 d-flex justify-content-center">
        {{ $kelas->links('pagination::bootstrap-4') }}
    </div> 
</div>

<!-- MODAL TAMBAH KELAS -->
<div class="modal fade" id="modalTambahKelas" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">{{ __('Tambah Kelas') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Nama Kelas') }}</label>
                        <input type="text" name="nama_kelas" class="form-control" required placeholder="Contoh: X MIPA 1">
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

<!-- MODAL EDIT KELAS -->
@foreach($kelas as $k)
<div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">{{ __('Edit Kelas') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kelas.update', $k->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Nama Kelas') }}</label>
                        <input type="text" name="nama_kelas" class="form-control" value="{{ $k->nama_kelas }}" required>
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
