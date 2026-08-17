@extends('adminlte::page')

@section('title', 'Daftar Guru')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-3">
    <h1 class="m-0 text-dark font-weight-bold">Penilaian Kinerja Guru</h1>
    <div class="form-inline d-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px; border: 1px solid #eaeaea;">
        <label for="tahun_ajaran_id" class="mr-2 mb-0 font-weight-bold text-dark ml-2">Tahun Ajaran:</label>
        <form action="{{ route('kepala.penilaian') }}" method="GET" class="mb-0">
            <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control border-0 bg-light mb-0" style="border-radius: 8px; font-weight: bold; min-width: 190px; width: auto;" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $selectedTahunId == $ta->id ? 'selected' : '' }}>
                        {{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h3 class="card-title text-secondary font-weight-bold"><i class="fas fa-users mr-2"></i>Periksa kinerja dan
            dokumen guru, dan Berikan penilaian kinerja guru</h3>
    </div>
    <div class="card-body p-4">
        <table id="guruTable" class="table table-hover border-0 w-100">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 px-4 py-3 text-center" width="5%">No</th>
                    <th class="border-0 py-3">NIP</th>
                    <th class="border-0 py-3">Nama Guru</th>
                    <th class="border-0 py-3">Mata Pelajaran</th>
                    <th class="border-0 py-3 text-center">Status Penilaian</th>
                    <th class="border-0 py-3 text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $guru)
                    <tr>
                        <td class="align-middle px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="align-middle py-3">{{ $guru->nip }}</td>
                        <td class="align-middle py-3 font-weight-bold text-dark">{{ $guru->name }}</td>
                        <td class="align-middle py-3 text-muted" style="line-height: 1.6; font-size: 0.95rem;">{!! $teachingInfo[$guru->id] !!}</td>
                        <td class="align-middle py-3 text-center">
                            @php
                                $totalAssignments = $assignmentsByGuru->has($guru->id) ? $assignmentsByGuru[$guru->id]->unique(function($item) { return $item->mapel_id . '-' . $item->kelas_id; })->count() : 0;
                                $completed = isset($completedPenilaians[$guru->id]) ? $completedPenilaians[$guru->id] : 0;
                            @endphp
                            @if($totalAssignments == 0)
                                <span class="badge badge-light border px-3 py-2 shadow-sm text-muted" style="border-radius: 6px;">Tidak ada mata pelajaran</span>
                            @elseif($completed == 0)
                                <span class="badge badge-secondary px-3 py-2 shadow-sm" style="border-radius: 6px;">Belum dinilai</span>
                            @elseif($completed >= $totalAssignments)
                                <span class="badge badge-success px-3 py-2 shadow-sm" style="border-radius: 6px;"><i class="fas fa-check mr-1"></i> Selesai</span>
                            @else
                                <span class="badge badge-info px-3 py-2 shadow-sm" style="border-radius: 6px;">{{ $completed }}/{{ $totalAssignments }}</span>
                            @endif
                        </td>
                        <td class="align-middle py-3 text-center">
                            <a href="{{ route('kepala.penilaian.show', ['id' => $guru->id, 'tahun_ajaran_id' => $selectedTahunId]) }}"
                                class="btn btn-sm btn-primary font-weight-bold shadow-sm px-3" style="border-radius: 6px;">
                                <i class="fas fa-edit mr-1"></i> Nilai
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#guruTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });
    });
</script>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
@stop