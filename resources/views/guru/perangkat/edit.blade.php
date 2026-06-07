@extends('adminlte::page')

@section('title', $template->nama_perangkat)

@section('content_header')
    <h1>{{ $template->nama_perangkat }}</h1>
@stop

@push('css')
<style>
    /* Lock the header area visually */
    .doc-header {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }
    .section-readonly {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 10px 14px;
        color: #495057;
        min-height: 38px;
        white-space: pre-wrap;
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
<div class="mb-3 d-flex align-items-center gap-2">
    <a href="{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id]) }}"
       class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <span class="text-muted">
        {{ $mapel->nama_mapel }} / {{ $kelas->nama_kelas }} / {{ $template->nama_perangkat }}
    </span>
</div>

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

@if($perangkatGuru->isSubmitted())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        Perangkat ini sudah disubmit pada {{ $perangkatGuru->submitted_at->format('d M Y H:i') }}.
        Anda tidak dapat mengubah isinya.
    </div>
@endif

{{-- Document header (read-only, admin-defined info) --}}
@if($template->id != 3)
<div class="doc-header">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><th width="140">Mata Pelajaran</th><td>: {{ $mapel->nama_mapel }}</td></tr>
                <tr><th>Kelas</th><td>: {{ $kelas->nama_kelas }}</td></tr>
                <tr><th>Guru</th><td>: {{ Auth::user()->name }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><th width="140">Tahun Ajaran</th><td>: {{ $perangkatGuru->tahun_ajaran }}</td></tr>
                <tr><th>Semester</th><td>: {{ $perangkatGuru->semester }}</td></tr>
                @if($perangkatGuru->bab > 0)
                <tr><th>Bab Ke-</th><td>: {{ $perangkatGuru->bab }}</td></tr>
                @endif
                <tr><th>Status</th>
                    <td>:
                        <span class="badge badge-{{ $perangkatGuru->statusBadgeClass() }}">
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

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @foreach($template->sections as $index => $section)
    @if($template->id == 3 && in_array($section->field_key, ['fase','elemen','deskripsi']))
        @continue
    @endif
            <div class="form-group {{ $index > 0 ? 'mt-4' : '' }}">

                <label for="field_{{ $section->field_key }}" class="font-weight-bold">
                    {{ $section->label }}
                    @if($section->is_required)
                        <span class="required-star">*</span>
                    @endif
                </label>

                @if($perangkatGuru->isSubmitted())
                    {{-- Read-only after submit --}}
                    <div class="section-readonly">
                        {!! nl2br(e($savedValues[$section->field_key] ?? '—')) !!}
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

                @elseif(in_array($section->field_type, ['textarea', 'richtext']))
                    @php
                        $content = $savedValues[$section->field_key] ?? '';
                        // If no content and template is CP, load default CP view
                        if (empty(trim($content)) && $template->nama_perangkat == 'Capaian Pembelajaran (CP)') {
                            $content = view('guru.perangkat.templates.cp_default', compact('mapel', 'kelas', 'perangkatGuru'))->render();
                        }
                    @endphp
                    @if($template->nama_perangkat == 'Capaian Pembelajaran (CP)')
                        <textarea id="field_{{ $section->field_key }}" name="{{ $section->field_key }}" class="form-control richtext-field" rows="20">{{ $content }}</textarea>
                    @else
                        <textarea
                            id="field_{{ $section->field_key }}"
                            name="{{ $section->field_key }}"
                            class="form-control {{ ($section->field_type === 'richtext') ? 'richtext-field' : '' }}"
                            rows="{{ $section->field_type === 'richtext' ? '20' : '5' }}"
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

    {{-- Sticky action bar --}}
    @if(!$perangkatGuru->isSubmitted())
    <div class="sticky-actions">
        <div class="d-flex align-items-center gap-2">

            <button type="button" id="btn-save" class="btn btn-secondary">
                <i class="fas fa-save"></i> Simpan
            </button>

            <a href="{{ route('guru.perangkat.print', $perangkatGuru->id) }}"
               target="_blank"
               class="btn btn-outline-dark">
                <i class="fas fa-print"></i> Cetak
            </a>

            <span id="save-indicator" class="text-muted small ml-2" style="display:none;">
                <i class="fas fa-circle-notch fa-spin"></i> Menyimpan...
            </span>

        </div>
    </div>
    @else
    <div class="mt-3">
        <a href="{{ route('guru.perangkat.print', $perangkatGuru->id) }}"
           target="_blank"
           class="btn btn-outline-dark">
            <i class="fas fa-print"></i> Cetak Dokumen
        </a>
    </div>
    @endif

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
    content_style: 'body { font-family:Arial,sans-serif; font-size:12pt; padding: 2cm; max-width: 21cm; margin: 0 auto; box-shadow: 0 0 5px rgba(0,0,0,0.1); background-color: #fff; } .mceNonEditable { opacity: 0.9; cursor: not-allowed; }'
});

const SAVE_URL   = "{!! route('guru.perangkat.save',   ['mapel' => $mapel->id, 'kelas' => $kelas->id, 'template' => $template->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran, 'semester' => $perangkatGuru->semester, 'bab' => $perangkatGuru->bab]) !!}";
const SUBMIT_URL = "{!! route('guru.perangkat.submit', ['mapel' => $mapel->id, 'kelas' => $kelas->id, 'template' => $template->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran, 'semester' => $perangkatGuru->semester, 'bab' => $perangkatGuru->bab]) !!}";

function getFormData() {
    if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
    }
    const form = document.getElementById('perangkat-form');
    return new FormData(form);
}

// Save draft
document.getElementById('btn-save')?.addEventListener('click', async function () {
    const indicator = document.getElementById('save-indicator');
    indicator.style.display = 'inline';
    this.disabled = true;

    try {
        const res = await fetch(SAVE_URL, {
            method: 'POST',
            body: getFormData(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (res.ok) {
            indicator.innerHTML = '<i class="fas fa-check text-success"></i> Tersimpan';
            setTimeout(() => {
                //window.location.href = "{{ route('guru.perangkat.kelas', [$mapel->id, $kelas->id, 'tahun_ajaran' => $perangkatGuru->tahun_ajaran]) }}";
            }, 1000);
        } else {
            Swal.fire('Gagal', 'Gagal menyimpan. Silakan coba lagi.', 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
    } finally {
        this.disabled = false;
    }
});
</script>
@endpush