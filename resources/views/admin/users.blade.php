@extends('adminlte::page')

@section('title', 'Manajemen User')

@section('content_header')
<h1>Manajemen User</h1>
@stop

@section('content')

@if(session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center w-100">

            <h3 class="card-title mb-0">
                Data User
            </h3>

            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahUser">

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
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Role</th>
                    <th>Password Default</th>
                    <th>Dibuat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nip }}</td>

                        <td>
                            @if($user->role == 'admin')
                                <span class="badge badge-danger">
                                    Admin
                                </span>

                            @elseif($user->role == 'kepala_sekolah')
                                <span class="badge badge-warning">
                                    Kepala Sekolah
                                </span>

                            @else
                                <span class="badge badge-success">
                                    Guru
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
                        <td class="project-actions text-right">

                            <!-- EDIT -->
                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#modalEdit{{ $user->id }}">
                                <i class="fas fa-pencil-alt">
                                    Edit
                                </i>
                            </a>
                            <!-- HAPUS -->
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus user ini?')">

                                    <i class="fas fa-trash">
                                        Delete
                                    </i>

                                </button>

                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada user
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-success">

                <h5 class="modal-title">
                    Tambah User
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
                        <label>Nama</label>

                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- NIP -->
                    <div class="form-group">
                        <label>NIP</label>

                        <input type="text" name="nip" class="form-control" required>
                    </div>

                    <!-- ROLE -->
                    <div class="form-group">
                        <label>Role</label>

                        <select name="role" class="form-control" required>

                            <option value="guru">
                                Guru
                            </option>

                            <option value="kepala">
                                Kepala Sekolah
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                        </select>
                    </div>
                    <!-- MAPEL + KELAS -->
                    <div class="form-group">

                        <label>
                            Mata Pelajaran & Kelas
                        </label>

                        <div id="mapel-wrapper">

                            <div class="row mb-2 mapel-item">

                                <!-- MAPEL -->
                                <div class="col-md-6">

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

                                <!-- KELAS -->
                                <div class="col-md-6">

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

                            </div>

                        </div>

                        <!-- BUTTON TAMBAH -->
                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="tambahMapelKelas()">

                            <i class="fas fa-plus"></i>
                            Tambah Mapel

                        </button>

                    </div>

                    <!-- WALI KELAS -->
                    <div class="form-group">

                        <label>
                            Wali Kelas
                        </label>

                        <div id="wali-wrapper">

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

                            </div>

                        </div>

                        <!-- BUTTON -->
                        <button type="button" class="btn btn-sm btn-warning mt-2" onclick="tambahWaliKelas()">

                            <i class="fas fa-plus"></i>
                            Tambah Wali Kelas

                        </button>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit" class="btn btn-success">

                        <i class="fas fa-save"></i>
                        Simpan

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
                            Edit User
                        </h5>

                        <button type="button" class="close" data-dismiss="modal">

                            <span>&times;</span>

                        </button>

                    </div>

<div class="modal-body">

    <!-- NAMA -->
    <div class="form-group">

        <label>Nama</label>

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

        <label>Role</label>

        <select name="role" class="form-control">

            <option value="guru"
                {{ $user->role == 'guru' ? 'selected' : '' }}>
                Guru
            </option>

            <option value="kepala_sekolah"
                {{ $user->role == 'kepala_sekolah' ? 'selected' : '' }}>
                Kepala Sekolah
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
            Mata Pelajaran & Kelas
        </label>

        <div id="edit-mapel-wrapper-{{ $user->id }}">

            @foreach($user->mapels as $index => $mapel)

                <div class="row mb-2">

                    <!-- MAPEL -->
                    <div class="col-md-5">

                        <select name="mapels[]" class="form-control">

                            @foreach($mapels as $m)

                                <option value="{{ $m->id }}"
                                    {{ $mapel->id == $m->id ? 'selected' : '' }}>

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
                                    {{ $user->kelas->contains($k->id) ? 'selected' : '' }}>

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
            Tambah Mapel

        </button>

    </div>



    <!-- WALI KELAS -->
    <div class="form-group">

        <label>
            Wali Kelas
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
            Tambah Wali

        </button>

    </div>

</div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-warning">

                            <i class="fas fa-save"></i>
                            Update

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