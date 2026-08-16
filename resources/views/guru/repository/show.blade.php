@extends('adminlte::page')

@section('title', 'Detail Kegiatan Repository')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Detail Kegiatan Repository</h1>
    <a href="{{ route('guru.repository') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    .detail-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .detail-image-wrapper {
        width: 100%;
        max-height: 500px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .detail-image-wrapper img {
        width: 100%;
        height: auto;
        object-fit: contain;
        max-height: 500px;
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

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card detail-card border-0">
            <div class="detail-image-wrapper">
                <img src="{{ asset('storage/' . $repo->foto_kegiatan) }}" alt="{{ $repo->judul }}">
            </div>
            
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
                    <div>
                        <div class="mb-3">
                            <span class="badge badge-success px-3 py-2 mr-1" style="font-size: 0.9rem;">
                                Semester {{ $repo->semester }}
                            </span>
                            <span class="badge badge-primary px-3 py-2" style="font-size: 0.9rem;">
                                Tahun Ajaran {{ $repo->tahun_ajaran }}
                            </span>
                        </div>
                        <h2 class="font-weight-bold text-dark">{{ $repo->judul }}</h2>
                    </div>
                    
                    <div class="d-flex flex-wrap mt-2 mt-md-0">
                        @if($repo->sertifikat)
                            <a href="{{ route('guru.repository.sertifikat', $repo->id) }}" target="_blank"
                                class="btn btn-success mr-2 mb-2">
                                <i class="fas fa-file-pdf mr-1"></i>Sertifikat
                            </a>
                        @endif
                        
                        <button class="btn btn-warning mr-2 mb-2 text-dark font-weight-bold" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        
                        <form action="{{ route('guru.repository.delete', $repo->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger mb-2" onclick="event.preventDefault(); Swal.fire({title: 'Hapus kegiatan ini?', text: 'Data tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="font-weight-bold mb-3">Deskripsi Kegiatan</h5>
                <p class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                    {!! nl2br(e($repo->deskripsi ?? 'Tidak ada deskripsi.')) !!}
                </p>
                
                <div class="mt-4 text-muted small">
                    <i class="fas fa-clock mr-1"></i> Ditambahkan pada: {{ $repo->created_at->format('d M Y, H:i') }}
                    @if($repo->updated_at != $repo->created_at)
                    <br><i class="fas fa-pencil-alt mr-1"></i> Terakhir diubah: {{ $repo->updated_at->format('d M Y, H:i') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('guru.repository.update', $repo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kegiatan</h5>
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
                                            <option value="{{ $ta->nama }}" {{ $repo->tahun_ajaran == $ta->nama ? 'selected' : '' }}>
                                                {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label class="d-block">Semester <span class="text-danger">*</span></label>
                                    <div class="btn-group-toggle d-flex" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary flex-fill {{ $repo->semester == 'Ganjil' ? 'active' : '' }}">
                                            <input type="radio" name="semester" value="Ganjil" autocomplete="off" {{ $repo->semester == 'Ganjil' ? 'checked' : '' }} required> Ganjil
                                        </label>
                                        <label class="btn btn-outline-secondary flex-fill {{ $repo->semester == 'Genap' ? 'active' : '' }}">
                                            <input type="radio" name="semester" value="Genap" autocomplete="off" {{ $repo->semester == 'Genap' ? 'checked' : '' }} required> Genap
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label>Judul Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" value="{{ $repo->judul }}" required placeholder="Contoh: Mengikuti Seminar Pendidikan Nasional">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail kegiatan...">{{ $repo->deskripsi }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Ubah Foto Kegiatan</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFotoEdit" name="foto_kegiatan" accept="image/*">
                                        <label class="custom-file-label" for="customFotoEdit">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG.</small>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Ubah Sertifikat</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customSertifikatEdit" name="sertifikat" accept=".pdf,image/*">
                                        <label class="custom-file-label" for="customSertifikatEdit">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah sertifikat. Format: PDF, JPG, PNG.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        </i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    // Update custom file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@stop
