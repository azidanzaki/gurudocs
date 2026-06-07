@extends('adminlte::page')

@section('title', 'History Perangkat')

@section('content_header')
    <h1>History Perangkat</h1>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('guru.perangkat.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title mb-0 font-weight-bold">Dokumen yang Sudah Pernah Disubmit</h4>    
        </div>
        
        <div class="ml-auto">
        <form method="GET" action="{{ route('guru.perangkat.history') }}" class="form-inline">
            <div class="form-group">
                <select name="tahun_ajaran" class="form-control" onchange="this.form.submit()">
                    <option value="semua" {{ $selectedTahun === 'semua' ? 'selected' : '' }}>Semua Tahun Ajaran</option>
                    @foreach($availableTahunAjarans as $tahun)
                        <option value="{{ $tahun }}" {{ $selectedTahun === $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
            <noscript><button type="submit" class="btn btn-primary">Pilih</button></noscript>
        </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="historyTable" class="table table-bordered table-hover mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Dokumen yang Disubmit</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Tanggal Disubmit</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->template->nama_perangkat }}</td>
                            <td>{{ $item->mapel->nama_mapel }}</td>
                            <td>{{ $item->kelas->nama_kelas }}</td>
                            <td>{{ $item->tahun_ajaran }}</td>
                            <td>{{ $item->submitted_at ? \Carbon\Carbon::parse($item->submitted_at)->format('d M Y, H:i') : '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('guru.perangkat.print', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#historyTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json",
                    search: "Cari:",
                }
            });
        });
    </script>
@endpush
