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

{{-- Main form --}}
<form id="perangkat-form" novalidate>
    @csrf

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @foreach($template->sections as $index => $section)
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
                    <textarea
                        id="field_{{ $section->field_key }}"
                        name="{{ $section->field_key }}"
                        class="form-control {{ $section->field_type === 'richtext' ? 'richtext-field' : '' }}"
                        rows="5"
                        placeholder="{{ $section->placeholder }}"
                        {{ $section->is_required ? 'required' : '' }}
                    >{{ $savedValues[$section->field_key] ?? '' }}</textarea>

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
                <i class="fas fa-save"></i> Simpan Draft
            </button>

            <button type="button" id="btn-submit" class="btn btn-success">
                <i class="fas fa-paper-plane"></i> Submit
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
<script>
const SAVE_URL   = "{{ route('guru.perangkat.save',   [$mapel->id, $kelas->id, $template->id]) }}";
const SUBMIT_URL = "{{ route('guru.perangkat.submit', [$mapel->id, $kelas->id, $template->id]) }}";

function getFormData() {
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
            // Show success briefly then hide
            indicator.innerHTML = '<i class="fas fa-check text-success"></i> Tersimpan';
            setTimeout(() => {
                indicator.style.display = 'none';
                indicator.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
            }, 2000);
        } else {
            alert('Gagal menyimpan. Silakan coba lagi.');
        }
    } catch (e) {
        alert('Terjadi kesalahan jaringan.');
    } finally {
        this.disabled = false;
    }
});

// Submit
document.getElementById('btn-submit')?.addEventListener('click', async function () {
    if (!confirm('Yakin ingin submit? Data tidak dapat diubah setelah disubmit.')) return;

    this.disabled = true;

    try {
        const res = await fetch(SUBMIT_URL, {
            method: 'POST',
            body: getFormData(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        if (res.redirected) {
            window.location.href = res.url;
        } else if (res.ok) {
            window.location.reload();
        } else {
            const data = await res.json();
            const msg  = data.errors?.submit ?? 'Gagal submit. Periksa kembali isian Anda.';
            alert(msg);
        }
    } catch (e) {
        alert('Terjadi kesalahan jaringan.');
    } finally {
        this.disabled = false;
    }
});
</script>
@endpush