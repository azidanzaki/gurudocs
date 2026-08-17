@extends('adminlte::page')

@section('title', 'Kelola Perangkat Pembelajaran')

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="font-weight-bold text-dark">Perangkat Pembelajaran</h1>
        <p class="text-muted mb-0">Atur tenggat waktu, dan pantau pengumpulan perangkat pembelajaran guru.</p>
    </div>
    <div class="form-inline mt-3 mt-md-0 d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
        <label for="tahun_ajaran" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
        
        <form action="{{ route('admin.kelolaperangkat') }}" method="GET" class="mb-0 mr-3">
            <select name="tahun_ajaran" class="form-control border-0 bg-light" style="border-radius: 8px; font-weight: bold;" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>
                        {{ $ta->nama }}
                    </option>
                @endforeach
            </select>
        </form>

        @if($nextTahunAjaran)
        <form action="{{ route('admin.kelolaperangkat.storeTahunAjaran') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="nama" value="{{ $nextTahunAjaran }}">
            <input type="hidden" name="redirect_to" value="admin.kelolaperangkat">
            <button type="button" class="btn btn-primary px-3 shadow-sm" style="border-radius: 8px;" onclick="Swal.fire({title: 'Tambah Tahun Ajaran?', text: 'Tambahkan tahun ajaran {{ $nextTahunAjaran }}?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, tambahkan!', cancelButtonText: 'Batal'}).then((result) => { if(result.isConfirmed) this.closest('form').submit(); })" title="Tambah Tahun Ajaran Baru">
                <i class="fas fa-plus mr-1"></i> Tambah TA ({{ $nextTahunAjaran }})
            </button>
        </form>
        @endif
    </div>
</div>
@stop

@section('content')

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="card-title font-weight-bold mb-0 text-dark">
            <i class="fas fa-list-ul text-info mr-2"></i> Daftar Perangkat & Tenggat Waktu
        </h5>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3" width="30%">Nama Perangkat</th>
                        <th class="border-0 py-3" width="30%">Tenggat Waktu</th>
                        <th class="border-0 py-3 text-center" width="40%">Aksi</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($templates as $template)
                @php
                    $tenggat = $template->tenggatWaktus->first();
                    if ($tenggat) {
                        $parsed = \Carbon\Carbon::parse($tenggat->tenggat_waktu);
                        $now = \Carbon\Carbon::now();
                        if ($parsed->isPast()) {
                            $sisaText = '<span class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i>Sudah lewat</span>';
                        } else {
                            $diff = $parsed->diff($now);
                            $days = $diff->d;
                            $hours = $diff->h;
                            $minutes = $diff->i;
                            $sisaParts = [];
                            if ($days > 0) $sisaParts[] = $days . ' hr';
                            if ($hours > 0) $sisaParts[] = $hours . ' jam';
                            if ($minutes > 0) $sisaParts[] = $minutes . ' mnt';
                            $sisaText = '<span class="text-success"><i class="fas fa-clock mr-1"></i>Sisa ' . implode(' ', $sisaParts) . '</span>';
                            if(empty($sisaParts)) $sisaText = '<span class="text-success"><i class="fas fa-clock mr-1"></i>Sisa < 1 mnt</span>';
                        }
                        $tenggatInfo = '<div class="font-weight-bold text-dark">' . $parsed->translatedFormat('d F Y, H:i') . ' WIB</div><div class="small mt-1">' . $sisaText . '</div>';
                        $btnClass = 'btn-outline-success';
                    } else {
                        $tenggatInfo = '<span class="badge badge-light border text-danger px-2 py-1" style="border-radius: 4px;"><i class="fas fa-times-circle mr-1"></i>Belum ada batas waktu</span>';
                        $btnClass = 'btn-outline-warning';
                    }
                @endphp
                <tr>
                    <td class="align-middle px-4 py-3 font-weight-bold text-dark">{{ $template->nama_perangkat }}</td>
                    <td class="align-middle py-3">
                        {!! $tenggatInfo !!}
                    </td>
                    <td class="align-middle text-center py-3">
                        <button class="btn btn-sm {{ $btnClass }} px-3 mr-2" style="border-radius: 6px;" data-toggle="modal" data-target="#modalTenggat{{ $template->id }}">
                            <i class="fas fa-calendar-alt mr-1"></i> {{ $tenggat ? 'Ubah Tenggat' : 'Atur Tenggat' }}
                        </button>
                        <button class="btn btn-sm btn-outline-info px-3" style="border-radius: 6px;" data-toggle="modal" data-target="#modalGuru{{ $template->id }}">
                            <i class="fas fa-users mr-1"></i> Tinjau Pengumpulan
                        </button>

                        {{-- Modal Atur Tenggat --}}
                        <div class="modal fade" id="modalTenggat{{ $template->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                    
                                    {{-- HEADER --}}
                                    <div class="modal-header bg-primary text-white border-0 py-3">
                                        <h5 class="modal-title font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-2"></i> Atur Tenggat Waktu
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8;">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    {{-- FORM --}}
                                    <form action="{{ route('admin.kelolaperangkat.updateTenggat') }}" method="POST"
                                        onsubmit="updateTenggatWaktu({{ $template->id }})">
                                        @csrf
                                        <div class="modal-body p-4 bg-light">
                                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                                            <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">

                                            {{-- PERANGKAT --}}
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark">Perangkat</label>
                                                <input type="text" class="form-control" style="border-radius: 8px; background-color: #e9ecef;"
                                                    value="{{ $template->nama_perangkat }}" readonly>
                                            </div>

                                            {{-- TENGGAT WAKTU --}}
                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold text-dark">Batas Akhir Pengumpulan</label>
                                                <input type="hidden" name="tenggat_waktu" id="tenggat_waktu_{{ $template->id }}"
                                                    value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('Y-m-d H:i:s') : '' }}">

                                                <div class="row">
                                                    {{-- TANGGAL --}}
                                                    <div class="col-md-6 mb-2">
                                                        <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-white border-right-0">
                                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control datepicker border-left-0"
                                                                id="tanggal_tenggat_{{ $template->id }}"
                                                                value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('Y-m-d') : date('Y-m-d') }}"
                                                                placeholder="Pilih Tanggal" required>
                                                        </div>
                                                    </div>

                                                    {{-- JAM --}}
                                                    <div class="col-md-6 mb-2">
                                                        <div class="input-group clockpicker shadow-sm" style="border-radius: 8px; overflow: hidden;"
                                                            data-placement="bottom" data-align="top" data-autoclose="true">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-white border-right-0">
                                                                    <i class="fas fa-clock text-warning"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control border-left-0"
                                                                id="jam_tenggat_{{ $template->id }}"
                                                                value="{{ $tenggat ? \Carbon\Carbon::parse($tenggat->tenggat_waktu)->format('H:i') : '23:59' }}"
                                                                placeholder="Pilih Jam" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    <i class="fas fa-info-circle mr-1"></i> Pilih tanggal dan jam batas akhir pengumpulan perangkat.
                                                </small>
                                            </div>
                                        </div>

                                        {{-- FOOTER --}}
                                        <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                                            <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                                                <i class="fas fa-save mr-1"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>

                        {{-- Modal List Guru --}}
                        <div class="modal fade" id="modalGuru{{ $template->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                    
                                    {{-- HEADER --}}
                                    <div class="modal-header bg-info text-white border-0 py-3">
                                        <h5 class="modal-title font-weight-bold">
                                            <i class="fas fa-users mr-2"></i> Pengumpulan: {{ $template->nama_perangkat }} <span class="badge badge-light text-info ml-2">{{ $selectedTahun }}</span>
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8;">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    {{-- BODY --}}
                                    <div class="modal-body p-0">
                                        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                            <div class="input-group" style="max-width: 300px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 8px 0 0 8px;">
                                                        <i class="fas fa-search text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control border-left-0 search-guru-input" data-template="{{ $template->id }}" style="border-radius: 0 8px 8px 0;" placeholder="Cari nama guru...">
                                            </div>
                                            <div class="spinner-border spinner-border-sm text-info d-none loading-guru-{{ $template->id }}" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                            <table class="table table-hover mb-0" id="tableGuru-{{ $template->id }}">
                                                <thead class="bg-white sticky-top" style="box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                    <tr>
                                                        <th class="border-0 px-4 cursor-pointer sort-guru" data-template="{{ $template->id }}" data-sort="name" data-dir="asc">Nama Guru <i class="fas fa-sort text-muted ml-1"></i></th>
                                                        <th class="border-0 cursor-pointer sort-guru" data-template="{{ $template->id }}" data-sort="status" data-dir="asc">Status <i class="fas fa-sort text-muted ml-1"></i></th>
                                                        <th class="border-0 text-center" width="180">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listGuruBody-{{ $template->id }}">
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">
                                                            <div class="spinner-border text-info mb-2" role="status"></div>
                                                            <p class="mb-0">Memuat data guru...</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>



@stop

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">
<style>
    .gap-2 { gap: 0.5rem; }
    .clockpicker-popover { z-index: 1060; } /* Ensure it shows above modal */
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
<script>
    function updateTenggatWaktu(id) {
        let tanggal = document.getElementById('tanggal_tenggat_' + id).value;
        let jam = document.getElementById('jam_tenggat_' + id).value;
        if(tanggal && jam) {
            document.getElementById('tenggat_waktu_' + id).value = tanggal + ' ' + jam + ':00';
        }
    }

    $(document).ready(function() {
        $('.datepicker').flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true,
            locale: "id"
        });
        $('.clockpicker').clockpicker({
            donetext: 'Selesai',
            autoclose: true
        });

        // AJAX Modal Guru Logic
        let searchTimeouts = {};

        function loadGuruList(templateId) {
            const modal = $('#modalGuru' + templateId);
            const search = modal.find('.search-guru-input').val();
            
            // Get active sort column
            let sortCol = 'name';
            let sortDir = 'asc';
            modal.find('.sort-guru').each(function() {
                if ($(this).find('i').hasClass('fa-sort-up') || $(this).find('i').hasClass('fa-sort-down')) {
                    sortCol = $(this).data('sort');
                    sortDir = $(this).data('dir');
                }
            });

            const tbody = $('#listGuruBody-' + templateId);
            const loading = $('.loading-guru-' + templateId);
            const tahunAjaran = '{{ $selectedTahun }}';

            loading.removeClass('d-none');
            tbody.css('opacity', '0.5');

            $.ajax({
                url: '{{ route("admin.kelolaperangkat.listGuruAjax") }}',
                data: {
                    template_id: templateId,
                    tahun_ajaran: tahunAjaran,
                    search: search,
                    sort: sortCol,
                    direction: sortDir
                },
                success: function(response) {
                    tbody.html(response);
                    tbody.css('opacity', '1');
                    loading.addClass('d-none');
                },
                error: function() {
                    tbody.html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data</td></tr>');
                    tbody.css('opacity', '1');
                    loading.addClass('d-none');
                }
            });
        }

        // When modal opens
        $('.modal[id^="modalGuru"]').on('shown.bs.modal', function () {
            const templateId = $(this).attr('id').replace('modalGuru', '');
            if ($('#listGuruBody-' + templateId).find('.spinner-border').length > 0) {
                loadGuruList(templateId);
            }
        });

        // On search input
        $('.search-guru-input').on('input', function() {
            const templateId = $(this).data('template');
            clearTimeout(searchTimeouts[templateId]);
            searchTimeouts[templateId] = setTimeout(function() {
                loadGuruList(templateId);
            }, 500);
        });

        // On sort click
        $('.sort-guru').on('click', function() {
            const templateId = $(this).data('template');
            const currentDir = $(this).data('dir');
            const newDir = currentDir === 'asc' ? 'desc' : 'asc';
            
            // Reset icons
            $('#modalGuru' + templateId).find('.sort-guru i').removeClass('fa-sort-up fa-sort-down text-info').addClass('fa-sort text-muted');
            
            // Set new sort direction
            $(this).data('dir', newDir);
            $(this).find('i').removeClass('fa-sort text-muted').addClass(newDir === 'asc' ? 'fa-sort-up text-info' : 'fa-sort-down text-info');
            
            loadGuruList(templateId);
        });
    });
</script>
@endpush