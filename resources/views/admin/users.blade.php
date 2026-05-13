@extends('adminlte::page')

@section('title', 'Manajemen User')

@section('content_header')
<h1>Manajemen User</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
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

                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>

                        </div>

                        <!-- NIP -->
                        <div class="form-group">

                            <label>NIP</label>

                            <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" required>

                        </div>

                        <!-- ROLE -->
                        <div class="form-group">

                            <label>Role</label>

                            <select name="role" class="form-control">

                                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>
                                    Guru
                                </option>

                                <option value="kepala" {{ $user->role == 'kepala' ? 'selected' : '' }}>
                                    Kepala Sekolah
                                </option>

                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                            </select>

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
@stop