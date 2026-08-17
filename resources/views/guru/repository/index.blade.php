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
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="font-weight-bold text-dark">Repository Kegiatan Guru</h1>
        <p class="text-muted mb-0">Kelola dokumentasi kegiatan, pelatihan, dan sertifikat.</p>
    </div>

    <div class="d-flex align-items-center flex-wrap mt-3 mt-md-0">
        <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm mr-3" style="border-radius: 12px; border: 1px solid #eaeaea;">
            <label for="tahun_ajaran" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
            <form action="{{ route('guru.repository') }}" method="GET" class="mb-0">
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-control border-0 bg-light mb-0" style="border-radius: 8px; font-weight: bold; min-width: 190px; width: auto;" onchange="this.form.submit()">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>
                            {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <button class="btn btn-success px-4 shadow-sm" style="border-radius: 8px;" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus mr-1"></i> Tambah Kegiatan
        </button>
    </div>
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
    
    /* Modern Radio Button UI for Semester */
    .btn-group-toggle .btn {
        border-radius: 8px;
        margin-right: 10px;
        border: 1px solid #ced4da;
        background-color: #fff;
        color: #495057;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-group-toggle .btn.active {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
        box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
    }
    .btn-group-toggle .btn:not(.active):hover {
        background-color: #f8f9fa;
    }
    .btn-group-toggle input[type="radio"] {
        display: none;
    }
    
    /* Modern Modal UI */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .modal-header {
        border-bottom: 1px solid #f1f3f5;
        background-color: #fff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid #f1f3f5;
        border-radius: 0 0 16px 16px;
        padding: 1.25rem 1.5rem;
    }
    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.6rem 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
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
            <a href="{{ route('guru.repository.show', $repo->id) }}" class="text-decoration-none text-dark">
                <div class="card repository-card border-0 h-100">

                    <div class="repository-image">
                        <img src="{{ asset('storage/' . $repo->foto_kegiatan) }}" class="w-100 h-100" style="object-fit:cover;">
                    </div>

                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge badge-success px-2 py-1">
                                {{ $repo->semester }}
                            </span>

                            <span class="badge badge-primary px-2 py-1">
                                {{ $repo->tahun_ajaran }}
                            </span>
                        </div>

                        <h5 class="font-weight-bold mt-2">
                            {{ $repo->judul }}
                        </h5>

                        <p class="text-muted small mt-2">
                            {{ Str::limit($repo->deskripsi, 100) }}
                        </p>
                    </div>

                    @if($repo->sertifikat)
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="badge badge-light border text-success px-2 py-1">
                            <i class="fas fa-file-pdf mr-1"></i> Ada Sertifikat
                        </span>
                    </div>
                    @endif

                </div>
            </a>
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


<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('guru.repository.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body bg-light">
                    <div class="card shadow-sm border-0 mb-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Tahun Ajaran <span class="text-danger">*</span></label>
                                    <select name="tahun_ajaran" class="form-control" required>
                                        @foreach($tahunAjarans as $ta)
                                            <option value="{{ $ta->nama }}" {{ ($activeTahun && $activeTahun->id == $ta->id) ? 'selected' : '' }}>
                                                {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label class="d-block">Semester <span class="text-danger">*</span></label>
                                    <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary flex-fill active">
                                            <input type="radio" name="semester" value="Ganjil" autocomplete="off" checked required> Ganjil
                                        </label>
                                        <label class="btn btn-outline-secondary flex-fill">
                                            <input type="radio" name="semester" value="Genap" autocomplete="off" required> Genap
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label>Judul Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" required placeholder="Contoh: Mengikuti Seminar Pendidikan Nasional">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail kegiatan..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Foto Kegiatan <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFotoTambah" name="foto_kegiatan" accept="image/*" required>
                                        <label class="custom-file-label" for="customFotoTambah">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG.</small>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Sertifikat (Opsional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customSertifikatTambah" name="sertifikat" accept=".pdf,image/*">
                                        <label class="custom-file-label" for="customSertifikatTambah">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: PDF, JPG, PNG.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        </i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.4/dist/dotlottie-wc.js" type="module"></script>
<script>
    // Update custom file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@stop