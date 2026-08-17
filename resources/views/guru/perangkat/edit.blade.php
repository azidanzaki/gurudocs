@extends('adminlte::page')

@section('title', $template->nama_perangkat)

@section('content_header')
    <h1>{{ $template->nama_perangkat }}</h1>
@stop

@push('css')
<style>
    /* Lock the header area visually */
    .doc-header {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    }
    .section-readonly {
        background: #f8f9fa;
        border: 1px solid #eaeaea;
        border-radius: 8px;
        padding: 12px 16px;
        color: #495057;
        min-height: 48px;
        white-space: pre-wrap;
    }
    .form-control {
        border-radius: 8px;
    }
    .required-star { color: #dc3545; }
    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: #fff;
        border-top: 1px solid #dee2e6;
        padding: 12px 0;
        z-index: 100;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div class="d-flex align-items-center">
        <a href="{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id]) }}"
           class="btn btn-light border px-3 shadow-sm mr-3" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <span class="text-muted font-weight-bold">
            {{ $mapel->nama_mapel }} <i class="fas fa-chevron-right mx-2 text-black-50" style="font-size: 10px;"></i> {{ $kelas->nama_kelas }} <i class="fas fa-chevron-right mx-2 text-black-50" style="font-size: 10px;"></i> <span class="text-dark">{{ $template->nama_perangkat }}</span>
        </span>
    </div>

    @if(!$perangkatGuru->is_completed)
    <div class="d-flex align-items-center flex-wrap gap-2">
        @if($perangkatGuru->status === 'revisi')
        <button type="button" class="btn btn-info shadow-sm mr-2" style="border-radius: 8px;" data-toggle="modal" data-target="#catatanModal">
            <i class="fas fa-comment-dots mr-1"></i> Catatan Revisi
        </button>
        @endif
        <button type="button" class="btn btn-success btn-submit-doc shadow-sm mr-2" style="border-radius: 8px;" data-id="{{ $perangkatGuru->id }}">
            <i class="fas fa-paper-plane mr-1"></i> Kumpulkan Perangkat
        </button>
        <button type="button" id="btn-reset" class="btn btn-danger shadow-sm mr-2" style="border-radius: 8px;">
            <i class="fas fa-trash mr-1"></i> Reset
        </button>
        <button type="button" id="btn-save-top" class="btn btn-primary shadow-sm mr-2" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        @if($perangkatGuru->status !== 'revisi')
        <button type="button" class="btn btn-outline-dark shadow-sm" style="border-radius: 8px;" onclick="document.getElementById('printFrame').contentWindow.print()">
            <i class="fas fa-print mr-1"></i> Cetak
        </button>
        @endif
        <span id="save-indicator-top" class="text-muted small ml-3 font-weight-bold" style="display:none;">
            <i class="fas fa-circle-notch fa-spin text-primary"></i> Menyimpan...
        </span>
    </div>
    @else
    <div class="d-flex align-items-center flex-wrap gap-2">
        @if($perangkatGuru->status === 'revisi')
        <button type="button" class="btn btn-info shadow-sm mr-2" style="border-radius: 8px;" data-toggle="modal" data-target="#catatanModal">
            <i class="fas fa-comment-dots mr-1"></i> Catatan Revisi
        </button>
        @endif
        @if($perangkatGuru->status !== 'revisi')
        <button type="button" class="btn btn-outline-dark shadow-sm" style="border-radius: 8px;" onclick="document.getElementById('printFrame').contentWindow.print()">
            <i class="fas fa-print mr-1"></i> Cetak Dokumen
        </button>
        @endif
    </div>
    @endif
</div>

<!-- Hidden Iframe untuk Cetak -->
<iframe id="printFrame" style="position: absolute; width: 1px; height: 1px; visibility: hidden; border: 0;" src="{{ route('guru.perangkat.print', $perangkatGuru->id) }}"></iframe>

<!-- Modal Catatan Revisi -->
@if($perangkatGuru->status === 'revisi')
<div class="modal fade" id="catatanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Revisi - {{ $template->nama_perangkat }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! nl2br(e($perangkatGuru->catatan_revisi ?? 'Tidak ada catatan.')) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if($errors->has('submit'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ $errors->first('submit') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if($perangkatGuru->is_completed)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Perangkat ini sudah dikunci pada {{ $perangkatGuru->submitted_at ? $perangkatGuru->submitted_at->format('d M Y H:i') : 'waktu yang lalu' }}.
        Anda tidak dapat mengubah isinya.
    </div>
@endif

{{-- Document header (read-only, admin-defined info) --}}
{{-- Document header (read-only, admin-defined info) --}}
@if($template->id != 3)
<div class="doc-header">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><th width="140" class="text-muted">Mata Pelajaran</th><td class="font-weight-bold">: {{ $mapel->nama_mapel }}</td></tr>
                <tr><th class="text-muted">Kelas</th><td class="font-weight-bold">: {{ $kelas->nama_kelas }}</td></tr>
                <tr><th class="text-muted">Guru</th><td class="font-weight-bold">: {{ Auth::user()->name }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><th width="140" class="text-muted">Tahun Ajaran</th><td class="font-weight-bold">: {{ $perangkatGuru->tahun_ajaran }}</td></tr>
                <tr><th class="text-muted">Semester</th><td class="font-weight-bold">: {{ $perangkatGuru->semester }}</td></tr>
                @if($perangkatGuru->bab > 0)
                <tr><th class="text-muted">Bab Ke-</th><td class="font-weight-bold">: {{ $perangkatGuru->bab }}</td></tr>
                @endif
                <tr><th class="text-muted align-middle">Status</th>
                    <td class="align-middle">:
                        <span class="badge badge-{{ $perangkatGuru->statusBadgeClass() }} px-2 py-1 ml-1" style="border-radius: 6px;">
                            {{ ucfirst($perangkatGuru->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Main form --}}
<form id="perangkat-form" novalidate>
    @csrf

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-4">

            @foreach($template->sections as $index => $section)
                @php
                    $isSkip = false;
                    $isRichtext = $section->field_type === 'richtext';
                    $defaultView = null;

                    if (str_contains($template->nama_perangkat, 'Capaian Pembelajaran')) {
                        if (in_array($section->field_key, ['fase','elemen','deskripsi'])) $isSkip = true;
                        if ($section->field_key == 'deskripsi_cp') { $isRichtext = true; $defaultView = 'cp_default'; }
                    } elseif (str_contains($template->nama_perangkat, 'Program Tahunan')) {
                        if (in_array($section->field_key, ['tp','alokasi_waktu'])) $isSkip = true;
                        if ($section->field_key == 'cp') { $isRichtext = true; $defaultView = 'prota_default'; }
                    } elseif (str_contains($template->nama_perangkat, 'Program Semester')) {
                        if (in_array($section->field_key, ['materi_pokok','waktu_pelaksanaan'])) $isSkip = true;
                        if ($section->field_key == 'tp') { $isRichtext = true; $defaultView = 'promes_default'; }
                    } elseif (str_contains($template->nama_perangkat, 'Tujuan Pembelajaran') && !str_contains($template->nama_perangkat, 'Alur') && !str_contains($template->nama_perangkat, 'Kriteria')) {
                        if (in_array($section->field_key, ['kompetensi','konten','rumusan_tp'])) $isSkip = true;
                        if ($section->field_key == 'cp') { $isRichtext = true; $defaultView = 'tp_default'; }
                    } elseif (str_contains($template->nama_perangkat, 'Alur Tujuan Pembelajaran')) {
                        if (in_array($section->field_key, ['alur','alokasi_waktu'])) $isSkip = true;
                        if ($section->field_key == 'tp') { $isRichtext = true; $defaultView = 'atp_default'; }
                    } elseif (str_contains($template->nama_perangkat, 'Modul Ajar')) {
                        $isRichtext = true;
                        $defaultView = 'rpp_default';
                    } elseif (str_contains($template->nama_perangkat, 'Soal Sumatif')) {
                        $isRichtext = true;
                        $defaultView = 'soal_default';
                    } elseif (str_contains($template->nama_perangkat, 'Kriteria Ketercapaian') || str_contains($template->nama_perangkat, 'KKTP')) {
                        if (in_array($section->field_key, ['indikator','interval'])) $isSkip = true;
                        if ($section->field_key == 'tp') { $isRichtext = true; $defaultView = 'kktp_default'; }
                    }
                @endphp

                @if($isSkip)
                    @continue
                @endif

            <div class="form-group {{ $index > 0 ? 'mt-4' : '' }}">

                <!-- <label for="field_{{ $section->field_key }}" class="font-weight-bold">
                    {{ $section->label }}
                    @if($section->is_required)
                        <span class="required-star">*</span>
                    @endif
                </label> -->

                @if($perangkatGuru->is_completed)
                    {{-- Read-only after submit --}}
                    <div class="section-readonly">
                        {!! html_entity_decode($savedValues[$section->field_key] ?? '—') !!}
                    </div>

                @elseif($section->field_type === 'date')
                    <input
                        type="date"
                        id="field_{{ $section->field_key }}"
                        name="{{ $section->field_key }}"
                        class="form-control"
                        value="{{ $savedValues[$section->field_key] ?? '' }}"
                        {{ $section->is_required ? 'required' : '' }}
                    >

                @elseif(in_array($section->field_type, ['textarea', 'richtext']) || $isRichtext)
                    @php
                        $content = $savedValues[$section->field_key] ?? '';
                        if (empty(trim($content)) && $defaultView) {
                            $content = view('guru.perangkat.templates.'.$defaultView, compact('mapel', 'kelas', 'perangkatGuru'))->render();
                        }
                    @endphp
                    @if($isRichtext)
                        <textarea id="field_{{ $section->field_key }}" name="{{ $section->field_key }}" class="form-control richtext-field" rows="20">{{ $content }}</textarea>
                    @else
                        <textarea
                            id="field_{{ $section->field_key }}"
                            name="{{ $section->field_key }}"
                            class="form-control"
                            rows="5"
                            placeholder="{{ $section->placeholder }}"
                            {{ $section->is_required ? 'required' : '' }}
                        >{{ $content }}</textarea>
                    @endif

                @else
                    <input
                        type="text"
                        id="field_{{ $section->field_key }}"
                        name="{{ $section->field_key }}"
                        class="form-control"
                        placeholder="{{ $section->placeholder }}"
                        value="{{ $savedValues[$section->field_key] ?? '' }}"
                        {{ $section->is_required ? 'required' : '' }}
                    >
                @endif

            </div>
            @endforeach

        </div>
    </div>



</form>

@stop

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '.richtext-field',
    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table directionality emoticons template noneditable',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | table | forecolor backcolor removeformat | pagebreak | fullscreen preview print',
    toolbar_mode: 'sliding',
    noneditable_class: 'mceNonEditable',
    height: 800,
    content_style: 'body { font-family:Arial,sans-serif; font-size:12pt; padding: 2cm; max-width: 21cm; margin: 0 auto; box-shadow: 0 0 5px rgba(0,0,0,0.1); background-color: #fff; } .mceNonEditable { opacity: 0.9; cursor: not-allowed; }',
    setup: function (editor) {
        editor.on('Change KeyUp', function () {
            debounceAutoSave();
        });
    }
});

const SAVE_URL   = "{!! route('guru.perangkat.save',   ['mapel' => $mapel->id, 'kelas' => $kelas->id, 'template' => $template->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran, 'semester' => $perangkatGuru->semester, 'bab' => $perangkatGuru->bab]) !!}";
const SUBMIT_URL = "{!! route('guru.perangkat.submit', ['mapel' => $mapel->id, 'kelas' => $kelas->id, 'template' => $template->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran, 'semester' => $perangkatGuru->semester, 'bab' => $perangkatGuru->bab]) !!}";
const RESET_URL  = "{!! route('guru.perangkat.reset',  ['mapel' => $mapel->id, 'kelas' => $kelas->id, 'template' => $template->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran, 'semester' => $perangkatGuru->semester, 'bab' => $perangkatGuru->bab]) !!}";

function getFormData() {
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
    }
    const form = document.getElementById('perangkat-form');
    return new FormData(form);
}

// Auto save logic
let autoSaveTimer;
const debounceAutoSave = () => {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        performSave(true);
    }, 1500); // 1.5 second delay after typing
};

// Bind normal inputs
document.querySelectorAll('#perangkat-form input, #perangkat-form textarea:not(.richtext-field)').forEach(el => {
    el.addEventListener('input', debounceAutoSave);
    el.addEventListener('change', debounceAutoSave);
});

async function performSave(isAutoSave = false) {
    const indicator = document.getElementById('save-indicator-top');
    const saveBtn = document.getElementById('btn-save-top');
    
    indicator.style.display = 'inline';
    indicator.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    
    if (!isAutoSave && saveBtn) {
        saveBtn.disabled = true;
    }

    try {
        const res = await fetch(SAVE_URL, {
            method: 'POST',
            body: getFormData(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (res.ok) {
            indicator.innerHTML = '<i class="fas fa-check text-success"></i> Tersimpan';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 2000);
        } else {
            if (!isAutoSave) Swal.fire('Gagal', 'Gagal menyimpan. Silakan coba lagi.', 'error');
            indicator.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Gagal menyimpan otomatis';
        }
    } catch (e) {
        if (!isAutoSave) Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
        indicator.innerHTML = '<i class="fas fa-exclamation-triangle text-danger"></i> Error jaringan';
    } finally {
        if (!isAutoSave && saveBtn) {
            saveBtn.disabled = false;
        }
    }
}

// Manual Save button click
document.getElementById('btn-save-top')?.addEventListener('click', function () {
    performSave(false);
});

// Reset
document.getElementById('btn-reset')?.addEventListener('click', function () {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Template yang sudah diubah tidak akan tersimpan dan akan kembali ke status belum diisi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                
                const res = await fetch(RESET_URL, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (res.ok) {
                    const data = await res.json();
                    window.location.href = data.redirect;
                } else {
                    Swal.fire('Gagal', 'Gagal mereset perangkat. Silakan coba lagi.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            }
        }
    });
});

// Kirim Perangkat
$('.btn-submit-doc').click(function() {
    var btn = $(this);
    var pgId = btn.data('id');

    Swal.fire({
        title: 'Kumpulkan Perangkat?',
        text: 'Perangkat yang sudah di kirim tidak bisa di ubah.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Kumpulkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i>');

            $.ajax({
                url: '/guru/perangkat/' + pgId + '/toggle-complete',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    force_complete: true
                },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        Swal.fire('Gagal', 'Gagal mengirim perangkat.', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Gagal mengirim perangkat.', 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Perangkat');
                }
            });
        }
    });
});
</script>
@endpush