@extends('adminlte::page')

@section('title', 'Dokumen')

@section('content_header')
    <h1 class="text-dark">Dokumen</h1>
@stop

@section('content')

<!-- 🔍 Search & Filter -->
<div class="card mb-4">
    <div class="card-body">

        <div class="row g-2">

            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari Dokumen">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <select class="form-control">
                    <option>Jenis Dokumen</option>
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-control">
                    <option>Mata Pelajaran</option>
                </select>
            </div>

        </div>

    </div>
</div>

<!-- 📄 RPP -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">RPP</h3>
    </div>

    <div class="card-body">
        <div class="row">

            @for ($i = 0; $i < 5; $i++)
                <div class="col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png"
                             class="card-img-top p-3" style="height: 180px; object-fit: contain;">

                        <div class="card-body text-center">
                            <p class="mb-0">RPP Quran Hadits Kelas IX</p>
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </div>
</div>

<!-- 📄 Silabus -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Silabus</h3>
    </div>

    <div class="card-body">
        <div class="row">

            @for ($i = 0; $i < 5; $i++)
                <div class="col-md-3 mb-3">
                    <div class="card h-100 shadow-sm">
                        <img src="https://www.iconpacks.net/icons/1/free-document-icon-901-thumb.png"
                             class="card-img-top p-3" style="height: 180px; object-fit: contain;">

                        <div class="card-body text-center">
                            <p class="mb-0">Silabus Quran Hadits Kelas IX</p>
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </div>
</div>

@stop