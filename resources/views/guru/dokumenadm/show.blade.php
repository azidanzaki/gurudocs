@extends('adminlte::page')

@section('title', $dokumen->judul)

@section('content_header')

<h1>{{ $dokumen->judul }}</h1>

@stop

@section('content')

<div class="card">

    <div class="card-body text-center">

        @php
            $ext = pathinfo($dokumen->file, PATHINFO_EXTENSION);
        @endphp

        {{-- PDF PREVIEW --}}
        @if($ext == 'pdf')

            <iframe src="{{ asset('storage/' . $dokumen->file_pdf) }}" width="100%" height="700px">
            </iframe>

        @else

            <i class="fas fa-file-word text-primary" style="font-size: 120px;"></i>

            <h4 class="mt-3">
                File Word
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

            {{-- DOWNLOAD WORD --}}
            @if($dokumen->file_word)

                <a href="{{ asset('storage/' . $dokumen->file_word) }}" download class="btn btn-primary">

                    <i class="fas fa-file-word"></i>
                    Download Word

                </a>

            @endif

        </div>

    </div>

</div>

@stop