@extends('adminlte::page')

@section('title', 'Template Dokumen')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="font-weight-bold text-dark"><i class="fas fa-folder-open text-primary mr-2"></i>Template Dokumen</h1>
            <p class="text-muted mb-0">Temukan dan unduh berbagai template dokumen administrasi yang disediakan.</p>
        </div>
    </div>
@stop

@section('content')

<style>
/* Card Dokumen */
.document-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,.05);
    transition: all .3s ease;
    cursor: pointer;
    border: 1px solid rgba(0,0,0,.05) !important;
}

.document-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0,0,0,.12);
    border-color: rgba(0,0,0,.1) !important;
}

.document-card:active {
    transform: scale(.98);
}

/* Preview PDF / Icon */
.document-card canvas,
.document-card i {
    transition: transform .35s ease;
}

.document-card:hover canvas,
.document-card:hover i.fa-file-word,
.document-card:hover i.fa-file-excel {
    transform: scale(1.1);
}

/* Judul */
.document-card .card-body p {
    transition: .3s;
}

.document-card:hover .card-body p {
    color: #007bff !important;
}

.document-preview {
    height: 180px;
    overflow: hidden;
    position: relative;
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.nav-pills-custom .nav-link {
    color: #6c757d;
    font-weight: 600;
    border-radius: 50px;
    padding: 8px 20px;
    margin-right: 10px;
    transition: all 0.2s;
    cursor: pointer;
}

.nav-pills-custom .nav-link.active {
    background-color: #007bff;
    color: #fff;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}

.nav-pills-custom .nav-link:hover:not(.active) {
    background-color: #e9ecef;
}

.filter-wrapper {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,.03);
    margin-bottom: 25px;
}
</style>

<!-- Filter & Search -->
<div class="filter-wrapper">
    <div class="row align-items-center">
        <div class="col-md-9 mb-3 mb-md-0">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" id="ajaxSearch" class="form-control bg-light border-left-0" style="border-radius: 0 8px 8px 0;" placeholder="Cari nama template dokumen..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0 d-flex align-items-center">
            <select id="ajaxTahun" class="form-control bg-light" style="border-radius: 8px; border: 1px solid #ced4da;">
                <option value="">Semua Tahun</option>
                @foreach ($tahunDokumen as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
            
            <div class="spinner-border spinner-border-sm text-primary ml-2" id="loadingSpinner" style="display: none;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-pills nav-pills-custom mb-4" id="custom-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link ajax-tab active" data-jenis="">
            <i class="fas fa-th-large mr-1"></i> Semua Dokumen
        </a>
    </li>
    @foreach ($jenisDokumen as $jenis)
        <li class="nav-item">
            <a class="nav-link ajax-tab" data-jenis="{{ $jenis }}">
                <i class="fas {{ str_contains(strtolower($jenis), 'non') ? 'fa-folder-minus' : 'fa-folder' }} mr-1"></i> {{ $jenis }}
            </a>
        </li>
    @endforeach
</ul>

<!-- Grid Dokumen -->
<div class="row" id="dokumenGrid">
    @include('guru.dokumenadm._grid')
</div>

@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    function renderPdfThumbnails() {
        document.querySelectorAll('.pdf-thumbnail').forEach(canvas => {
            if(canvas.dataset.rendered === "true") return; // Skip if already rendered
            canvas.dataset.rendered = "true";
            
            const url = canvas.dataset.pdfUrl;
            const container = canvas.parentElement;
            const spinner = container.querySelector('.pdf-loading-spinner');
            const fallbackIcon = container.querySelector('.pdf-fallback-icon');

            canvas.style.display = 'none';

            pdfjsLib.getDocument(url).promise.then(pdf => {
                return pdf.getPage(1);
            }).then(page => {
                const viewport = page.getViewport({ scale: 0.5 });
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
    }

    $(document).ready(function() {
        // Initial render
        renderPdfThumbnails();
        
        // AJAX Realtime Filter
        let searchTimeout;
        let currentJenis = '';

        function fetchDokumen() {
            const search = $('#ajaxSearch').val();
            const tahun = $('#ajaxTahun').val();
            
            $('#loadingSpinner').show();
            $('#dokumenGrid').css('opacity', '0.5');
            
            $.ajax({
                url: "{{ route('guru.dokumenadmguru.index') }}",
                data: {
                    search: search,
                    tahun_dokumen: tahun,
                    jenis_dokumen: currentJenis
                },
                success: function(response) {
                    $('#dokumenGrid').html(response).css('opacity', '1');
                    $('#loadingSpinner').hide();
                    
                    // Render PDFs for newly loaded content
                    renderPdfThumbnails();
                },
                error: function() {
                    $('#dokumenGrid').css('opacity', '1');
                    $('#loadingSpinner').hide();
                }
            });
        }

        $('#ajaxSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchDokumen, 500);
        });

        $('#ajaxTahun').on('change', fetchDokumen);
        
        $('.ajax-tab').on('click', function() {
            $('.ajax-tab').removeClass('active');
            $(this).addClass('active');
            currentJenis = $(this).data('jenis');
            fetchDokumen();
        });
    });
</script>
@stop
