@if ($errors->any())
    <div class="alert alert-danger">

        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>
@endif
@extends('adminlte::page')

@section('title', 'Repository Guru')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Repository Kegiatan Guru</h1>

    <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus"></i> Tambah Kegiatan
    </button>
</div>
@stop

@section('content')

<style>
    .repository-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        transition: all .3s ease;
        cursor: pointer;
    }

    .repository-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .15);
    }

    .repository-card:active {
        transform: scale(.98);
    }

    /* Gambar ikut animasi */
    .repository-card img {
        transition: transform .4s ease;
    }

    .repository-card:hover img {
        transform: scale(1.05);
    }

    /* Judul berubah warna */
    .repository-card h5 {
        transition: .3s;
    }

    .repository-card:hover h5 {
        color: #198754;
    }

    /* Tombol sedikit naik */
    .repository-card .btn {
        transition: .25s;
    }

    .repository-card:hover .btn {
        transform: translateY(-2px);
    }

    .repository-image {
        height: 220px;
        overflow: hidden;
    }

    .repository-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .45s ease;
    }

    .repository-card:hover .repository-image img {
        transform: scale(1.08);
    }
</style>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">

    @forelse($repositories as $repo)

        <div class="col-md-4 mb-4">

            <div class="card repository-card border-0 h-100">

                <div class="repository-image">
                    <img src="{{ asset('storage/' . $repo->foto_kegiatan) }}" class="w-100 h-100" style="object-fit:cover;">
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        <span class="badge badge-success">
                            {{ $repo->semester }}
                        </span>

                        <span class="badge badge-primary">
                            {{ $repo->tahun_ajaran }}
                        </span>
                    </div>

                    <h5 class="font-weight-bold">
                        {{ $repo->judul }}
                    </h5>

                    <p class="text-muted small">
                        {{ Str::limit($repo->deskripsi, 100) }}
                    </p>

                </div>

                <div class="card-footer bg-white border-0">

                    <div class="d-flex flex-wrap align-items-center">

                        @if($repo->sertifikat)
                            <a href="{{ route('guru.repository.sertifikat', $repo->id) }}" target="_blank"
                                class="btn btn-sm btn-success mr-2 mb-2">
                                <i class="fas fa-file"></i>
                                Sertifikat
                            </a>
                        @endif

                        <button class="btn btn-sm btn-warning mr-2 mb-2" data-toggle="modal"
                            data-target="#editModal{{ $repo->id }}">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>

                        <form action="{{ route('guru.repository.delete', $repo->id) }}" method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-sm btn-danger mr-2 mb-2"
                                onclick="event.preventDefault(); Swal.fire({title: 'Hapus kegiatan ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>





        <div class="modal fade" id="editModal{{ $repo->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <form action="{{ route('guru.repository.update', $repo->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5>Edit Kegiatan</h5>

                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label>Tahun Ajaran</label>

                                <input type="text" name="tahun_ajaran" class="form-control"
                                    value="{{ $repo->tahun_ajaran }}">
                            </div>

                            <div class="form-group">
                                <label>Semester</label>

                                <select name="semester" class="form-control">
                                    <option value="Ganjil" {{ $repo->semester == 'Ganjil' ? 'selected' : '' }}>
                                        Ganjil
                                    </option>

                                    <option value="Genap" {{ $repo->semester == 'Genap' ? 'selected' : '' }}>
                                        Genap
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Judul</label>

                                <input type="text" name="judul" class="form-control" value="{{ $repo->judul }}">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>

                                <textarea name="deskripsi" class="form-control" rows="4">{{ $repo->deskripsi }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Kegiatan</label>

                                <input type="file" name="foto_kegiatan" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Sertifikat</label>

                                <input type="file" name="sertifikat" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer">

                            <button class="btn btn-primary">
                                Update
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>

    @empty

        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center py-5">

                    <div class="mb-3">
                        <dotlottie-wc src="https://lottie.host/4c3882ec-8e3f-4c3f-aab1-a7269cb96a4e/b2OJRr3lGn.lottie"
                            style="width:220px;height:220px;margin:auto" autoplay loop>
                        </dotlottie-wc>
                    </div>

                    <h4>Belum Ada Kegiatan</h4>

                    <p class="text-muted">
                        Tambahkan dokumentasi kegiatan, pelatihan, seminar,
                        workshop, dan sertifikat guru.
                    </p>

                </div>

            </div>

        </div>

    @endforelse

</div>


<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('guru.repository.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="modal-header">
                    <h5>Tambah Kegiatan</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Tahun Ajaran</label>

                        <input type="text" name="tahun_ajaran" class="form-control" placeholder="2025/2026" required>
                    </div>

                    <div class="form-group">
                        <label>Semester</label>

                        <select name="semester" class="form-control" required>

                            <option value="">-- Pilih Semester --</option>

                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Judul Kegiatan</label>

                        <input type="text" name="judul" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>

                        <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto Kegiatan</label>

                        <input type="file" name="foto_kegiatan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Sertifikat (Opsional)</label>

                        <input type="file" name="sertifikat" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-success">
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.4/dist/dotlottie-wc.js" type="module"></script>
@stop