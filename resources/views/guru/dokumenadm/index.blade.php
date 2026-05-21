@extends('adminlte::page')

@section('title', 'Dokumen')

@section('content_header')
<h1 class="text-dark">Dokumen</h1>
@stop

@section('content')

<!-- 🔍 Search & Filter -->
<form method="GET" action="{{ route('guru.dokumenadmguru.index') }}">

    <div class="row g-2">

        <div class="col-md-8">

            <div class="input-group">

                <input type="text" name="search" class="form-control" placeholder="Cari Dokumen"
                    value="{{ request('search') }}">

                <button class="btn btn-primary">

                    <i class="fas fa-search"></i>

                </button>

            </div>

        </div>

        <div class="col-md-4">

            <select name="jenis_dokumen" class="form-control" onchange="this.form.submit()">

                <option value="">
                    Semua Jenis Dokumen
                </option>

                @foreach ($jenisDokumen as $jenis)

                    <option value="{{ $jenis }}" {{ request('jenis_dokumen') == $jenis ? 'selected' : '' }}>

                        {{ $jenis }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

</form>

<!-- Dokumen Tersedia -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Dokumen Tersedia</h3>
    </div>

    <div class="card-body">
        <div class="row">

            @foreach ($dokumen as $item)
                <div class="col-md-3 mb-3">

                    <a href="{{ route('guru.dokumenadmguru.show', $item->id) }}" class="text-decoration-none text-dark">

                        <div class="card h-100 shadow-sm">

                            <div class="text-center p-4">

                                @php
                                    $ext = pathinfo($item->file, PATHINFO_EXTENSION);
                                @endphp

                                @if($ext == 'pdf')

                                    <i class="fas fa-file-pdf text-danger" style="font-size: 80px;"></i>

                                @else

                                    <i class="fas fa-file-word text-primary" style="font-size: 80px;"></i>

                                @endif

                            </div>

                            <div class="card-body text-center">

                                <p class="mb-0">
                                    {{ $item->judul }}
                                </p>

                            </div>

                        </div>

                    </a>

                </div>
            @endforeach

        </div>
    </div>
</div>
@stop