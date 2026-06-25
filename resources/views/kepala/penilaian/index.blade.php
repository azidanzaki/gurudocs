@extends('adminlte::page')

@section('title', 'Daftar Guru')

@section('content_header')
    <h1>Daftar Guru untuk Penilaian</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pilih Guru untuk Dilihat Profil/Penilaiannya</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Status Penilaian</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $guru)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $guru->nip }}</td>
                        <td>{{ $guru->name }}</td>
                        <td>
                            <span class="badge badge-secondary px-2 py-1">Belum Dinilai</span>
                        </td>
                        <td>
                            <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-sm btn-primary font-weight-bold">
                                <i class="fas fa-edit mr-1"></i> Nilai
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop