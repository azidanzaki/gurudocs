@extends('adminlte::page')

@section('title', 'Dokumen')

@section('content_header')
<h1 class="text-dark">Dokumen</h1>
@stop

@section('content')

<style>
/* Card Dokumen */
.document-card{
    background:#fff;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:all .3s ease;
    cursor:pointer;
}

.document-card:hover{
    transform:translateY(-8px);
    box-shadow:0 12px 28px rgba(0,0,0,.15);
}

.document-card:active{
    transform:scale(.97);
}

/* Preview PDF / Icon */
.document-card canvas,
.document-card i{
    transition:transform .35s ease;
}

.document-card:hover canvas,
.document-card:hover i{
    transform:scale(1.08);
}

/* Judul */
.document-card .card-body p{
    transition:.3s;
}

.document-card:hover .card-body p{
    color:#198754;
}

/* Badge */
.document-card .badge{
    transition:.3s;
}

.document-card:hover .badge{
    background:#198754;
    color:#fff !important;
}
.document-preview{
    height:180px;
    overflow:hidden;
    position:relative;
}

.document-preview canvas{
    transition:transform .4s ease;
}

.document-card:hover .document-preview canvas{
    transform:scale(1.06);
}
a.text-decoration-none{
    display:block;
}
</style>

<!-- 🔍 Search & Filter -->
<form method="GET" action="{{ route('guru.dokumenadmguru.index') }}" class="mb-4">

    <div class="row g-2">

        <div class="col-md-8">

            <div class="input-group">

                <input type="text" name="search" class="form-control" placeholder="Cari Dokumen"
                    value="{{ request('search') }}">

                <input type="hidden" name="jenis_dokumen" value="{{ request('jenis_dokumen') }}">

                <button class="btn btn-primary">

                    <i class="fas fa-search"></i>

                </button>

            </div>

        </div>

        <div class="col-md-4">

            <select name="tahun_dokumen" class="form-control" onchange="this.form.submit()">

                <option value="">
                    Semua Tahun Dokumen
                </option>

                @foreach ($tahunDokumen as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun_dokumen') == $tahun ? 'selected' : '' }}>
                        Tahun {{ $tahun }}
                    </option>
                @endforeach

            </select>

        </div>

    </div>

</form>

<!-- Dokumen Tersedia (Tampilan Tabs) -->
<div class="card card-success card-outline card-outline-tabs mb-4">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ !request('jenis_dokumen') ? 'active' : '' }}" 
                   href="{{ route('guru.dokumenadmguru.index', ['search' => request('search'), 'tahun_dokumen' => request('tahun_dokumen')]) }}">
                    Semua Dokumen
                </a>
            </li>
            @foreach ($jenisDokumen as $jenis)
                <li class="nav-item">
                    <a class="nav-link {{ request('jenis_dokumen') == $jenis ? 'active' : '' }}" 
                       href="{{ route('guru.dokumenadmguru.index', ['jenis_dokumen' => $jenis, 'search' => request('search'), 'tahun_dokumen' => request('tahun_dokumen')]) }}">
                        {{ $jenis }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card-body">
        <div class="row">

            @forelse ($dokumen as $item)
                <div class="col-md-3 mb-3">

                    <a href="{{ route('guru.dokumenadmguru.show', $item->id) }}" class="text-decoration-none text-dark">

                        <div class="card document-card h-100 border-0">

                            <div class="document-preview text-center p-3 d-flex align-items-center justify-content-center bg-light" style="height: 180px; overflow: hidden; position: relative;">

                                @php
                                    $wordExt = $item->file_word ? pathinfo($item->file_word, PATHINFO_EXTENSION) : '';
                                    $isExcel = in_array($wordExt, ['xls', 'xlsx']);
                                @endphp

                                @if($item->file_pdf)
                                    <canvas class="pdf-thumbnail" data-pdf-url="{{ asset('storage/' . $item->file_pdf) }}" style="max-width: 100%; max-height: 100%; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border: 1px solid #ddd;"></canvas>
                                    <div class="pdf-loading-spinner spinner-border spinner-border-sm text-success" role="status" style="position: absolute;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }} pdf-fallback-icon" style="font-size: 60px; display: none;"></i>
                                @else
                                    <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }}" style="font-size: 60px;"></i>
                                @endif

                            </div>

                            <div class="card-body text-center bg-white">

                                <p class="mb-0 font-weight-bold text-truncate" style="font-size: 0.95rem;">
                                    {{ $item->judul }}
                                </p>
                                <span class="badge badge-light text-muted mt-1" style="font-size: 0.75rem;">
                                    {{ $item->jenis_dokumen }}
                                </span>

                            </div>

                        </div>

                    </a>

                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                    <p class="text-muted">Tidak ada dokumen yang ditemukan.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    document.querySelectorAll('.pdf-thumbnail').forEach(canvas => {
        const url = canvas.dataset.pdfUrl;
        const container = canvas.parentElement;
        const spinner = container.querySelector('.pdf-loading-spinner');
        const fallbackIcon = container.querySelector('.pdf-fallback-icon');

        // Hide canvas initially
        canvas.style.display = 'none';

        pdfjsLib.getDocument(url).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({ scale: 0.4 });
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            return page.render(renderContext).promise;
        }).then(() => {
            canvas.style.display = 'block';
            if (spinner) spinner.style.display = 'none';
        }).catch(err => {
            console.error('Error rendering PDF thumbnail:', err);
            if (spinner) spinner.style.display = 'none';
            if (fallbackIcon) fallbackIcon.style.display = 'block';
        });
    });
</script>
@stop