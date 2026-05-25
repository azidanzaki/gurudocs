@extends('adminlte::page')

@section('title', $dokumen->judul)

@section('content_header')

<h1>{{ $dokumen->judul }}</h1>

@stop

@section('content')

<div class="card">

    <div class="card-body text-center">

        <div class="alert alert-info border-0 shadow-sm text-left mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Catatan:</strong> Dokumen di bawah ini hanya merupakan berkas pratinjau (preview). Untuk mengisi atau menggunakan template ini, silakan unduh dokumen aslinya menggunakan tombol download di bawah.
        </div>

        @php
            $ext = $dokumen->file_pdf ? 'pdf' : ($dokumen->file_word ? 'docx' : '');
            $wordExt = $dokumen->file_word ? pathinfo($dokumen->file_word, PATHINFO_EXTENSION) : '';
            $isExcel = in_array($wordExt, ['xls', 'xlsx']);
        @endphp

        {{-- PDF PREVIEW --}}
        @if($ext == 'pdf')

            <iframe src="{{ asset('storage/' . $dokumen->file_pdf) }}" width="100%" height="700px">
            </iframe>

        @else

            <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }}" style="font-size: 120px;"></i>

            <h4 class="mt-3">
                File {{ $isExcel ? 'Excel' : 'Word' }}
            </h4>

        @endif

        <div class="mt-4">

            {{-- DOWNLOAD PDF --}}
            @if($dokumen->file_pdf)

                <a href="{{ asset('storage/' . $dokumen->file_pdf) }}" download class="btn btn-danger">

                    <i class="fas fa-file-pdf"></i>
                    Download PDF

                </a>

            @endif

            {{-- DOWNLOAD TEMPLATE (WORD / EXCEL) --}}
            @if($dokumen->file_word)

                <a href="{{ asset('storage/' . $dokumen->file_word) }}" download class="btn {{ $isExcel ? 'btn-success' : 'btn-primary' }}">

                    <i class="fas {{ $isExcel ? 'fa-file-excel' : 'fa-file-word' }}"></i>
                    Download {{ $isExcel ? 'Excel' : 'Word' }}

                </a>

            @endif

        </div>

    </div>

</div>

@stop