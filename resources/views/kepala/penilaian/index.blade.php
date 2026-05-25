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
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
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
                            <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Lihat Profil
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop