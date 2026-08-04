@extends('adminlte::page')

@section('title', 'Kelola Dokumen Administratif')

@section('content_header')
<h1>Kelola Dokumen Administratif</h1>
@stop

@section('content')

@if(session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center w-100">

            <h3 class="card-title mb-0">
                Data Dokumen Administratif
            </h3>

            <button class="btn btn-primary mt-2 mt-md-0" data-toggle="modal" data-target="#modalUploadDokumen">

                <i class="fas fa-plus"></i>
                Tambah Dokumen

            </button>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Judul Dokumen</th>
                    <th>Jenis</th>
                    <th>Tahun</th>
                    <th>Dibuat</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($dokumen as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->judul }}
                        </td>

                        <td>

                            @if($item->jenis_dokumen == 'Dokumen Administratif')
                                <span class="badge badge-success">
                                    Dokumen Administratif
                                </span>
                            @else
                                <span class="badge badge-info">
                                    Dokumen Non Administratif
                                </span>
                            @endif

                        </td>

                        <td>
                            {{ $item->tahun ?? '-' }}
                        </td>

                        <td>
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="text-right">

                            {{-- LIHAT PDF --}}
                            @if($item->file_pdf)

                                <a href="{{ asset('storage/' . $item->file_pdf) }}" target="_blank" class="btn btn-info btn-sm">

                                    <i class="fas fa-eye"></i>
                                    Lihat

                                </a>

                            @endif

                            {{-- DOWNLOAD TEMPLATE --}}
                            @if($item->file_word)
                                @php
                                    $wordExt = pathinfo($item->file_word, PATHINFO_EXTENSION);
                                    $isExcel = in_array($wordExt, ['xls', 'xlsx']);
                                @endphp
                                <a href="{{ asset('storage/' . $item->file_word) }}" class="btn btn-success btn-sm">

                                    <i class="fas {{ $isExcel ? 'fa-file-excel' : 'fa-file-word' }}"></i>
                                    {{ $isExcel ? 'Excel' : 'Word' }}

                                </a>

                            @endif

                            {{-- HAPUS --}}
                            <form action="{{ route('admin.dokumenadm.delete', $item->id) }}" method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="event.preventDefault(); Swal.fire({title: 'Hapus dokumen ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">

                                    <i class="fas fa-trash"></i>
                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center text-muted">

                            Belum ada dokumen

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- MODAL UPLOAD --}}
<div class="modal fade" id="modalUploadDokumen" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-success">

                <h5 class="modal-title">
                    Tambah Dokumen
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            {{-- FORM --}}
            <form action="{{ route('admin.dokumenadm.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    {{-- JUDUL --}}
                    <div class="form-group">

                        <label>
                            Judul Dokumen
                        </label>

                        <input type="text" name="judul" class="form-control" required>

                    </div>

                    {{-- JENIS --}}
                    <div class="form-group">

                        <label>
                            Jenis Dokumen
                        </label>

                        <select name="jenis_dokumen" class="form-control" required>

                            <option value="">
                                -- Pilih Jenis --
                            </option>

                            <option value="Dokumen Administratif">
                                Dokumen Administratif
                            </option>

                            <option value="Dokumen Non Administratif">
                                Dokumen Non Administratif
                            </option>

                        </select>

                    </div>

                    {{-- TAHUN --}}
                    <div class="form-group">

                        <label>
                            Tahun Dokumen
                        </label>

                        <input type="number" name="tahun" class="form-control" min="2000" max="2100" step="1"
                            placeholder="Contoh: 2026" required>

                    </div>

                    {{-- FILE --}}
                    <div class="form-group">

                        <label>
                            File Dokumen
                        </label>

                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx"
                            required>

                        <small class="text-muted d-block">
                            Format: PDF, DOC, DOCX, XLS, XLSX (Max 20MB)
                        </small>
                        <small class="text-info font-weight-bold d-block mt-1">
                            Catatan: Format file yang Anda unggah adalah format asli yang akan diunduh langsung oleh
                            user (Guru).
                        </small>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit" class="btn btn-success">

                        <i class="fas fa-paper-plane"></i>
                        Kirim

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    setTimeout(function () {

        let alertBox = document.getElementById('success-alert');

        if (alertBox) {
            $(alertBox).alert('close');
        }

    }, 5000);

</script>

@stop