@extends('adminlte::page')

@section('title', 'Kelola Dokumen')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold text-dark"><i class="fas fa-folder-open text-primary mr-2"></i>Kelola Dokumen Admin</h1>
            <p class="text-muted mb-0">Kelola semua template dokumen administrasi untuk diunduh oleh guru.</p>
        </div>
    </div>
@stop

@section('content')
<style>
.custom-radio-btn input[type="radio"] {
    display: none;
}
.custom-radio-btn label {
    display: inline-block;
    padding: 10px 20px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid #ced4da;
    background-color: #fff;
    color: #495057;
    transition: all 0.2s ease-in-out;
}
.custom-radio-btn input[type="radio"]:checked + label {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0,123,255,0.2);
}
.custom-radio-btn label:first-of-type {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
.custom-radio-btn label:last-of-type {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}
</style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" id="success-alert" role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #28a745;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
        
        <div class="card-header bg-white border-bottom-0 py-4 d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="card-title font-weight-bold mb-0 text-dark w-100 mb-3">
                Daftar Dokumen
            </h3>
            
            <div class="w-100 d-flex flex-wrap align-items-center gap-3" style="gap: 15px;">
                <div class="input-group" style="max-width: 350px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="ajaxSearch" class="form-control bg-light border-left-0" style="border-radius: 0 8px 8px 0;" placeholder="Cari nama template dokumen...">
                </div>
                
                <select id="ajaxTahun" class="form-control bg-light" style="border-radius: 8px; max-width: 200px; border: 1px solid #ced4da;">
                    <option value="">Semua Tahun Ajaran</option>
                    @php $currentYear = date('Y'); @endphp
                    @for($i = -2; $i <= 5; $i++)
                        @php 
                            $y = $currentYear - $i; 
                            $yearLabel = $y . '/' . ($y+1);
                        @endphp
                        <option value="{{ $yearLabel }}">{{ $yearLabel }}</option>
                    @endfor
                </select>
                
                <div class="spinner-border spinner-border-sm text-primary ml-2" id="loadingSpinner" style="display: none;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                
                <button type="button" class="btn btn-primary px-4 shadow-sm ml-auto" data-toggle="modal" data-target="#modalUploadDokumen" style="border-radius: 8px;">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Tambah Dokumen
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3" width="5%">No</th>
                            <th class="border-0 py-3 cursor-pointer sort-header" data-sort="judul" width="30%">Judul Dokumen <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 text-center cursor-pointer sort-header" data-sort="jenis_dokumen" width="20%">Jenis <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 text-center cursor-pointer sort-header" data-sort="tahun" width="10%">Tahun <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 text-center" width="35%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @include('admin.keloladokumenadm._table')
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3" id="paginationContainer">
            @if($dokumen->hasPages())
                {{ $dokumen->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>

    {{-- MODAL UPLOAD --}}
    <div class="modal fade" id="modalUploadDokumen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-cloud-upload-alt mr-2"></i> Tambah Template Dokumen</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.dokumenadm.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="card border-0 shadow-sm mb-0" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                {{-- JUDUL --}}
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Judul Dokumen <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="judul" id="judul_dokumen" class="form-control" style="border-radius: 8px 0 0 8px;" placeholder="Masukkan judul..." required>
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white" style="border-radius: 0 8px 8px 0;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="gunakan_nama_file">
                                                    <label class="custom-control-label cursor-pointer user-select-none" for="gunakan_nama_file" style="font-size: 0.9rem;">Samakan nama file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- JENIS --}}
                                        <div class="form-group mb-4">
                                            <label class="d-block font-weight-bold text-dark mb-2">Jenis Dokumen <span class="text-danger">*</span></label>
                                            <div class="custom-radio-btn d-flex">
                                                <input type="radio" id="jenis1" name="jenis_dokumen" value="Dokumen Administratif" required>
                                                <label for="jenis1" class="flex-fill text-center m-0">Administratif</label>
                                                
                                                <input type="radio" id="jenis2" name="jenis_dokumen" value="Dokumen Non Administratif" required>
                                                <label for="jenis2" class="flex-fill text-center m-0" style="border-left: 0;">Non Administratif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- TAHUN --}}
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-dark">Tahun Ajaran <span class="text-danger">*</span></label>
                                            <select name="tahun" class="form-control" style="border-radius: 8px;" required>
                                                <option value="" disabled selected>Pilih Tahun Ajaran...</option>
                                                @php $currentYear = date('Y'); @endphp
                                                @for($i = -2; $i <= 5; $i++)
                                                    @php 
                                                        $y = $currentYear - $i; 
                                                        $yearLabel = $y . '/' . ($y+1);
                                                    @endphp
                                                    <option value="{{ $yearLabel }}">{{ $yearLabel }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- FILE --}}
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-dark">Pilih File Template <span class="text-danger">*</span></label>
                                    <div class="custom-file mb-2">
                                        <input type="file" name="file" id="file_dokumen" class="custom-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                        <label class="custom-file-label" for="file_dokumen" style="border-radius: 8px;">Browse file...</label>
                                    </div>
                                    <div class="alert alert-info border-0 shadow-sm mt-3 mb-0" style="border-radius: 8px; border-left: 4px solid #17a2b8 !important;">
                                        <small><i class="fas fa-info-circle mr-1"></i> Format didukung: <strong>PDF, DOC, DOCX, XLS, XLSX</strong> (Max 20MB).</small><br>
                                        <small><i class="fas fa-check-circle mr-1"></i> File ini adalah master template yang akan diunduh oleh guru.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-1"></i> Simpan Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
    setTimeout(function () {
        let alertBox = document.getElementById('success-alert');
        if (alertBox) {
            $(alertBox).alert('close');
        }
    }, 5000);

    $(document).ready(function() {
        if(typeof bsCustomFileInput !== 'undefined') {
            bsCustomFileInput.init();
        }
        
        // --- Form Modal Upload ---
        let originalTitle = '';
        
        $('#file_dokumen').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            fileName = fileName.substring(0, fileName.lastIndexOf('.')) || fileName; 
            
            if ($('#gunakan_nama_file').is(':checked')) {
                $('#judul_dokumen').val(fileName);
            }
        });

        $('#gunakan_nama_file').on('change', function() {
            if ($(this).is(':checked')) {
                originalTitle = $('#judul_dokumen').val(); 
                
                var fileInput = $('#file_dokumen')[0];
                if (fileInput.files && fileInput.files[0]) {
                    var fileName = fileInput.files[0].name;
                    fileName = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
                    $('#judul_dokumen').val(fileName).prop('readonly', true);
                } else {
                    $('#judul_dokumen').val('').prop('readonly', true);
                }
            } else {
                $('#judul_dokumen').val(originalTitle).prop('readonly', false);
            }
        });
        
        // --- AJAX Table Filters ---
        let searchTimeout;
        let currentSortColumn = 'created_at';
        let currentSortDirection = 'desc';

        function fetchDokumen(page = 1) {
            const search = $('#ajaxSearch').val();
            const tahun = $('#ajaxTahun').val();
            
            $('#loadingSpinner').show();
            
            $.ajax({
                url: "{{ route('admin.dokumenadm.index') }}",
                data: {
                    search: search,
                    tahun: tahun,
                    sort: currentSortColumn,
                    direction: currentSortDirection,
                    page: page
                },
                success: function(response) {
                    $('#tableBody').html(response);
                    
                    // Extract pagination from the hidden row in the partial view
                    const paginationHtml = $('#tableBody .pagination-row td').html();
                    if(paginationHtml && paginationHtml.trim() !== '') {
                        $('#paginationContainer').html(paginationHtml);
                    } else {
                        $('#paginationContainer').empty();
                    }
                    
                    $('#loadingSpinner').hide();
                },
                error: function() {
                    $('#loadingSpinner').hide();
                }
            });
        }

        $('#ajaxSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchDokumen(1), 500);
        });

        $('#ajaxTahun').on('change', function() {
            fetchDokumen(1);
        });
        
        $('.sort-header').on('click', function() {
            const column = $(this).data('sort');
            if (currentSortColumn === column) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortColumn = column;
                currentSortDirection = 'asc';
            }
            
            // Update icons
            $('.sort-header i').removeClass('fa-sort-up fa-sort-down text-primary').addClass('fa-sort text-muted');
            const iconClass = currentSortDirection === 'asc' ? 'fa-sort-up text-primary' : 'fa-sort-down text-primary';
            $(this).find('i').removeClass('fa-sort text-muted').addClass(iconClass);
            
            fetchDokumen(1);
        });

        // Handle pagination links
        $(document).on('click', '#paginationContainer a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            fetchDokumen(page);
        });
        
        // Initialize sort icon
        $('.sort-header').css('cursor', 'pointer');
    });
</script>
@stop
