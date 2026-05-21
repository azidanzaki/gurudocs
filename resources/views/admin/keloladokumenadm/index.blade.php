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

            <button class="btn btn-success"
                    data-toggle="modal"
                    data-target="#modalUploadDokumen">

                <i class="fas fa-upload"></i>
                Upload Dokumen

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

                            @if($item->jenis_dokumen == 'RPP')
                                <span class="badge badge-primary">
                                    RPP
                                </span>

                            @elseif($item->jenis_dokumen == 'Modul Ajar')
                                <span class="badge badge-success">
                                    Modul Ajar
                                </span>

                            @elseif($item->jenis_dokumen == 'Silabus')
                                <span class="badge badge-warning">
                                    Silabus
                                </span>

                            @elseif($item->jenis_dokumen == 'Prota')
                                <span class="badge badge-danger">
                                    Prota
                                </span>

                            @else
                                <span class="badge badge-info">
                                    {{ $item->jenis_dokumen }}
                                </span>
                            @endif

                        </td>

                        <td>
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="text-right">

                            {{-- LIHAT PDF --}}
                            @if($item->file_pdf)

                                <a href="{{ asset('storage/' . $item->file_pdf) }}"
                                   target="_blank"
                                   class="btn btn-info btn-sm">

                                    <i class="fas fa-eye"></i>
                                    Lihat

                                </a>

                            @endif

                            {{-- DOWNLOAD WORD --}}
                            @if($item->file_word)

                                <a href="{{ asset('storage/' . $item->file_word) }}"
                                   class="btn btn-success btn-sm">

                                    <i class="fas fa-download"></i>
                                    Word

                                </a>

                            @endif

                            {{-- HAPUS --}}
                            <form action="{{ route('admin.dokumenadm.delete', $item->id) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus dokumen ini?')">

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
<div class="modal fade"
     id="modalUploadDokumen"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-lg"
         role="document">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-success">

                <h5 class="modal-title">
                    Upload Dokumen
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            {{-- FORM --}}
            <form action="{{ route('admin.dokumenadm.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    {{-- JUDUL --}}
                    <div class="form-group">

                        <label>
                            Judul Dokumen
                        </label>

                        <input type="text"
                               name="judul"
                               class="form-control"
                               required>

                    </div>

                    {{-- JENIS --}}
                    <div class="form-group">

                        <label>
                            Jenis Dokumen
                        </label>

                        <select name="jenis_dokumen"
                                class="form-control"
                                required>

                            <option value="">
                                -- Pilih Jenis --
                            </option>

                            <option value="RPP">
                                RPP
                            </option>

                            <option value="Modul Ajar">
                                Modul Ajar
                            </option>

                            <option value="Silabus">
                                Silabus
                            </option>

                            <option value="Prota">
                                Prota
                            </option>

                            <option value="Promes">
                                Promes
                            </option>

                        </select>

                    </div>

                    {{-- FILE --}}
                    <div class="form-group">

                        <label>
                            File Dokumen
                        </label>

                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".pdf,.doc,.docx"
                               required>

                        <small class="text-muted">
                            Format: PDF, DOC, DOCX (Max 20MB)
                        </small>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="fas fa-save"></i>
                        Upload

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