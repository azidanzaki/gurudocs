@extends('adminlte::page')

@section('title', __('Manajemen User'))

@section('content_header')
<h1>{{ __('Manajemen User') }}</h1>
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

    <form action="{{ route('admin.users') }}" method="GET" class="form-inline">

        <input type="text"
               name="search"
               class="form-control mr-2"
               placeholder="Cari nama, NIP atau role..."
               value="{{ request('search') }}">

        <button class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
        </button>

        @if(request('search'))
            <a href="{{ route('admin.users') }}" class="btn btn-secondary ml-2">
                Reset
            </a>
        @endif

    </form>

    <button class="btn btn-primary mt-2 mt-md-0"
            data-toggle="modal"
            data-target="#modalTambahUser">

        <i class="fas fa-plus"></i>
        Tambah User

    </button>

</div>

    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ __('Nama') }}</th>
                    <th>NIP</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Password Default') }}</th>
                    <th>{{ __('Dibuat') }}</th>
                    <th width="250">{{ __('Aksi') }}</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nip }}</td>

                        <td>
                            @if($user->role == 'admin')
                                <span class="badge badge-danger">
                                    Admin
                                </span>

                            @elseif($user->role == 'kepala_sekolah')
                                <span class="badge badge-warning">
                                    {{ __('Kepala Sekolah') }}
                                </span>

                            @else
                                <span class="badge badge-success">
                                    {{ __('Guru') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">
                                    {{ __('Aktif') }}
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    {{ __('Nonaktif') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ $user->default_password }}
                            </span>
                        </td>

                        <td>
                            {{ $user->created_at->format('d-M-Y H:i:s') }}
                        </td>
                        <td class="project-actions text-center">

                            <!-- EDIT -->
                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#modalEdit{{ $user->id }}">
                                <i class="fas fa-pencil-alt"></i> {{ __('Edit') }}
                            </a>
                            <!-- TOGGLE AKTIF / NONAKTIF -->
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                @if($user->is_active)
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="event.preventDefault(); Swal.fire({title: '{{ __('Nonaktifkan user ini?') }}', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, nonaktifkan!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                        <i class="fas fa-user-slash"></i> {{ __('Nonaktifkan') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="event.preventDefault(); Swal.fire({title: '{{ __('Aktifkan user ini?') }}', icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, aktifkan!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                        <i class="fas fa-user-check"></i> {{ __('Aktifkan') }}
                                    </button>
                                @endif

                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center">
                            {{ __('Belum ada user') }}
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
    <div class="mt-3 d-flex justify-content-center">
    {{ $users->links('pagination::bootstrap-4') }}
</div> 
</div>
<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-success">

                <h5 class="modal-title">
                    {{ __('Tambah User') }}
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <!-- FORM -->
            <form action="{{ route('admin.users.store') }}" method="POST">

                @csrf

                <div class="modal-body">

                    <!-- NAMA -->
                    <div class="form-group">
                        <label>{{ __('Nama') }}</label>

                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- NIP -->
                    <div class="form-group">
                        <label>NIP</label>

                        <input type="text" name="nip" class="form-control" required>
                    </div>

                    <!-- ROLE -->
                    <div class="form-group">
                        <label>{{ __('Role') }}</label>

                        <select name="role" class="form-control" required>

                            <option value="guru">
                                {{ __('Guru') }}
                            </option>

                            <option value="kepala">
                                {{ __('Kepala Sekolah') }}
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                        </select>
                    </div>
                    <!-- MAPEL + KELAS -->
                    <div class="form-group">

                        <label>
                            {{ __('Mata Pelajaran & Kelas') }}
                        </label>

                        <div id="mapel-wrapper">

                            <div class="row mb-2 mapel-item">

                                <!-- MAPEL -->
                                <div class="col-md-6">

                                    <select name="mapels[]" class="form-control">

                                        <option value="">
                                            {{ __('-- Pilih Mapel --') }}
                                        </option>

                                        @foreach($mapels as $mapel)

                                            <option value="{{ $mapel->id }}">
                                                {{ $mapel->nama_mapel }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <!-- KELAS -->
                                <div class="col-md-6">

                                    <select name="kelas[]" class="form-control">

                                        <option value="">
                                            {{ __('-- Pilih Kelas --') }}
                                        </option>

                                        @foreach($kelas as $k)

                                            <option value="{{ $k->id }}">
                                                {{ $k->nama_kelas }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <!-- BUTTON TAMBAH -->
                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="tambahMapelKelas()">

                            <i class="fas fa-plus"></i>
                            {{ __('Tambah Mapel') }}

                        </button>

                    </div>

                    <!-- WALI KELAS -->
                    <div class="form-group">

                        <label>
                            {{ __('Wali Kelas') }}
                        </label>

                        <div id="wali-wrapper">

                            <div class="row mb-2 wali-item">

                                <div class="col-md-10">

                                    <select name="wali_kelas[]" class="form-control">

                                        <option value="">
                                            {{ __('-- Pilih Kelas --') }}
                                        </option>

                                        @foreach($kelas as $k)

                                            <option value="{{ $k->id }}">
                                                {{ $k->nama_kelas }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button type="button" class="btn btn-sm btn-warning mt-2" onclick="tambahWaliKelas()">

                            <i class="fas fa-plus"></i>
                            {{ __('Tambah Wali Kelas') }}

                        </button>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">

                        {{ __('Batal') }}

                    </button>

                    <button type="submit" class="btn btn-success">

                        <i class="fas fa-save"></i>
                        {{ __('Simpan') }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@foreach($users as $user)

    <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-warning">

                        <h5 class="modal-title">
                            {{ __('Edit User') }}
                        </h5>

                        <button type="button" class="close" data-dismiss="modal">

                            <span>&times;</span>

                        </button>

                    </div>

<div class="modal-body">

    <!-- NAMA -->
    <div class="form-group">

        <label>{{ __('Nama') }}</label>

        <input type="text"
               name="name"
               class="form-control"
               value="{{ $user->name }}"
               required>

    </div>

    <!-- NIP -->
    <div class="form-group">

        <label>NIP</label>

        <input type="text"
               name="nip"
               class="form-control"
               value="{{ $user->nip }}"
               required>

    </div>

    <!-- ROLE -->
    <div class="form-group">

        <label>{{ __('Role') }}</label>

        <select name="role" class="form-control">

            <option value="guru"
                {{ $user->role == 'guru' ? 'selected' : '' }}>
                {{ __('Guru') }}
            </option>

            <option value="kepala_sekolah"
                {{ $user->role == 'kepala_sekolah' ? 'selected' : '' }}>
                {{ __('Kepala Sekolah') }}
            </option>

            <option value="admin"
                {{ $user->role == 'admin' ? 'selected' : '' }}>
                Admin
            </option>

        </select>

    </div>



    <!-- MAPEL & KELAS -->
    <div class="form-group">

        <label>
            {{ __('Mata Pelajaran & Kelas') }}
        </label>

        <div id="edit-mapel-wrapper-{{ $user->id }}">

            @foreach($user->mengajar() as $index => $mengajar)

                <div class="row mb-2">

                    <!-- MAPEL -->
                    <div class="col-md-5">

                        <select name="mapels[]" class="form-control">

                            @foreach($mapels as $m)

                                <option value="{{ $m->id }}"
                                    {{ $mengajar->mapel_id == $m->id ? 'selected' : '' }}>

                                    {{ $m->nama_mapel }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- KELAS -->
                    <div class="col-md-5">

                        <select name="kelas[]" class="form-control">

                            @foreach($kelas as $k)

                                <option value="{{ $k->id }}"
                                    {{ $mengajar->kelas_id == $k->id ? 'selected' : '' }}>

                                    {{ $k->nama_kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- HAPUS -->
                    <div class="col-md-2">

                        <button type="button"
                                class="btn btn-danger btn-block"
                                onclick="hapusItem(this)">

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                </div>

            @endforeach

        </div>

        <!-- BUTTON -->
        <button type="button"
                class="btn btn-sm btn-primary mt-2"
                onclick="tambahEditMapel({{ $user->id }})">

            <i class="fas fa-plus"></i>
            {{ __('Tambah Mapel') }}

        </button>

    </div>



    <!-- WALI KELAS -->
    <div class="form-group">

        <label>
            {{ __('Wali Kelas') }}
        </label>

        <div id="edit-wali-wrapper-{{ $user->id }}">

            @foreach($user->waliKelas as $wali)

                <div class="row mb-2">

                    <div class="col-md-10">

                        <select name="wali_kelas[]" class="form-control">

                            @foreach($kelas as $k)

                                <option value="{{ $k->id }}"
                                    {{ $wali->id == $k->id ? 'selected' : '' }}>

                                    {{ $k->nama_kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button type="button"
                                class="btn btn-danger btn-block"
                                onclick="hapusItem(this)">

                            <i class="fas fa-trash"></i>

                        </button>

                    </div>

                </div>

            @endforeach

        </div>

        <!-- BUTTON -->
        <button type="button"
                class="btn btn-sm btn-warning mt-2"
                onclick="tambahEditWali({{ $user->id }})">

            <i class="fas fa-plus"></i>
            {{ __('Tambah Wali Kelas') }}

        </button>

    </div>

</div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-warning">

                            <i class="fas fa-save"></i>
                            {{ __('Update') }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endforeach

<script>

function tambahMapelKelas()
{
    let html = `
    
    <div class="row mb-2 mapel-item">

        <div class="col-md-5">

            <select name="mapels[]" class="form-control">

                <option value="">
                    -- Pilih Mapel --
                </option>

                @foreach($mapels as $mapel)

                    <option value="{{ $mapel->id }}">
                        {{ $mapel->nama_mapel }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-5">

            <select name="kelas[]" class="form-control">

                <option value="">
                    -- Pilih Kelas --
                </option>

                @foreach($kelas as $k)

                    <option value="{{ $k->id }}">
                        {{ $k->nama_kelas }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-2">

            <button type="button"
                    class="btn btn-danger btn-block"
                    onclick="hapusItem(this)">

                <i class="fas fa-trash"></i>

            </button>

        </div>

    </div>
    `;

    $('#mapel-wrapper').append(html);
}



function tambahWaliKelas()
{
    let html = `
    
    <div class="row mb-2 wali-item">

        <div class="col-md-10">

            <select name="wali_kelas[]" class="form-control">

                <option value="">
                    -- Pilih Kelas --
                </option>

                @foreach($kelas as $k)

                    <option value="{{ $k->id }}">
                        {{ $k->nama_kelas }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-2">

            <button type="button"
                    class="btn btn-danger btn-block"
                    onclick="hapusItem(this)">

                <i class="fas fa-trash"></i>

            </button>

        </div>

    </div>
    `;

    $('#wali-wrapper').append(html);
}



function hapusItem(button)
{
    $(button).closest('.row').remove();
}

</script>
<script>

function tambahEditMapel(userId)
{
    let html = `
    
    <div class="row mb-2">

        <div class="col-md-5">

            <select name="mapels[]" class="form-control">

                @foreach($mapels as $m)

                    <option value="{{ $m->id }}">
                        {{ $m->nama_mapel }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-5">

            <select name="kelas[]" class="form-control">

                @foreach($kelas as $k)

                    <option value="{{ $k->id }}">
                        {{ $k->nama_kelas }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-2">

            <button type="button"
                    class="btn btn-danger btn-block"
                    onclick="hapusItem(this)">

                <i class="fas fa-trash"></i>

            </button>

        </div>

    </div>
    `;

    $('#edit-mapel-wrapper-' + userId).append(html);
}



function tambahEditWali(userId)
{
    let html = `
    
    <div class="row mb-2">

        <div class="col-md-10">

            <select name="wali_kelas[]" class="form-control">

                @foreach($kelas as $k)

                    <option value="{{ $k->id }}">
                        {{ $k->nama_kelas }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-2">

            <button type="button"
                    class="btn btn-danger btn-block"
                    onclick="hapusItem(this)">

                <i class="fas fa-trash"></i>

            </button>

        </div>

    </div>
    `;

    $('#edit-wali-wrapper-' + userId).append(html);
}



function hapusItem(button)
{
    $(button).closest('.row').remove();
}

</script>

<script>
    setTimeout(function () {

        let alertBox = document.getElementById('success-alert');

        if (alertBox) {
            $(alertBox).alert('close');
        }

    }, {{ session('timeout', 5000) }});
</script>
@stop