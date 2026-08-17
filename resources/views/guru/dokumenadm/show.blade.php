@extends('adminlte::page')

@section('title', $dokumen->judul)

@section('content_header')
    <div class="d-flex align-items-center mb-2">
        <a href="{{ route('guru.dokumenadmguru.index') }}" class="btn btn-light btn-sm shadow-sm mr-3" style="border-radius: 50px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <h1 class="font-weight-bold text-dark mb-1">{{ $dokumen->judul }}</h1>
            <p class="text-muted mb-0">
                <span class="badge badge-light border mr-2">{{ $dokumen->jenis_dokumen }}</span>
                <span class="badge badge-light border">Tahun {{ $dokumen->tahun ?? date('Y') }}</span>
            </p>
        </div>
    </div>
@stop

@section('content')

<div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">

    <div class="card-body p-0">
        
        <div class="bg-light p-4 border-bottom text-center">
            @php
                $ext = $dokumen->file_pdf ? 'pdf' : ($dokumen->file_word ? 'docx' : '');
                $wordExt = $dokumen->file_word ? pathinfo($dokumen->file_word, PATHINFO_EXTENSION) : '';
                $isExcel = in_array($wordExt, ['xls', 'xlsx']);
                
                $btnColor = $isExcel ? 'success' : 'primary';
                $iconCls = $isExcel ? 'fa-file-excel' : 'fa-file-word';
                $fileType = $isExcel ? 'Excel' : 'Word';
            @endphp
            
            <h5 class="font-weight-bold text-dark mb-4">Unduh File Template</h5>
            
            <div class="d-flex justify-content-center gap-3" style="gap: 15px;">
                @if($dokumen->file_word)
                    <a href="{{ asset('storage/' . $dokumen->file_word) }}" download class="btn btn-{{ $btnColor }} shadow-sm font-weight-bold px-4 py-2" style="border-radius: 50px;">
                        <i class="fas {{ $iconCls }} mr-2"></i> Download {{ $fileType }}
                    </a>
                @endif
                
                @if($dokumen->file_pdf)
                    <a href="{{ asset('storage/' . $dokumen->file_pdf) }}" download class="btn btn-outline-danger shadow-sm font-weight-bold px-4 py-2" style="border-radius: 50px;">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>
                @endif
            </div>
        </div>

        <div class="p-0 bg-secondary" style="min-height: 500px;">
            @if($ext == 'pdf')
                <iframe src="{{ asset('storage/' . $dokumen->file_pdf) }}" width="100%" height="800px" style="border: none;">
                </iframe>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 bg-white">
                    <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }}" style="font-size: 150px; opacity: 0.9;"></i>
                    <h4 class="mt-4 font-weight-bold text-dark">Pratinjau Tidak Tersedia</h4>
                    <p class="text-muted text-center" style="max-width: 400px;">
                        File ini adalah format {{ $fileType }}. Pratinjau langsung di dalam browser hanya didukung untuk format PDF.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@stop
