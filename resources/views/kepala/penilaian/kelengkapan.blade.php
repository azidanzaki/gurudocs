@extends('adminlte::page')

@section('title', 'Kelengkapan Dokumen - ' . $guru->name)

@section('content_header')
    <h1>Kelengkapan Dokumen & Kegiatan - {{ $guru->name }}</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('kepala.penilaian.show', $guru->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Profil
    </a>
</div>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-header border-0 d-flex align-items-center">
        <h3 class="card-title font-weight-bold m-0"><i class="fas fa-file-alt mr-2 text-primary"></i> Dokumen Administrasi / Perangkat Pembelajaran</h3>
        <form action="{{ route('kepala.penilaian.kelengkapan', $guru->id) }}" method="GET" class="d-flex ml-auto" id="filterForm">
            <select name="mapel_kelas" class="form-control form-control-sm mr-2" onchange="document.getElementById('filterForm').submit();" style="width: 250px; border-radius: 6px;">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mengajarAssignments as $assignment)
                    @php
                        $m = $semuaMapels->get($assignment->mapel_id);
                        $k = $kelases->get($assignment->kelas_id);
                        $val = $assignment->mapel_id . '-' . $assignment->kelas_id;
                    @endphp
                    @if($m && $k)
                        <option value="{{ $val }}" {{ $selectedMapelKelas == $val ? 'selected' : '' }}>{{ $m->nama_mapel }} ({{ $k->nama_kelas }})</option>
                    @endif
                @endforeach
            </select>
            <select name="tahun_ajaran" class="form-control form-control-sm" onchange="document.getElementById('filterForm').submit();" style="width: 180px; border-radius: 6px;">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->nama }}" {{ $selectedTahun == $ta->nama ? 'selected' : '' }}>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive p-3">
            <table id="dokumenTable" class="table table-hover m-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Dokumen</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $dokumen)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle font-weight-bold">{{ $dokumen->template->nama_perangkat ?? 'Template tidak ditemukan' }}</td>
                            <td class="align-middle">{{ $dokumen->mapel->nama_mapel ?? '-' }}</td>
                            <td class="align-middle">{{ $dokumen->kelas->nama_kelas ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @php
                                    $badgeClass = 'secondary';
                                    $statusText = 'Belum Dibuat';
                                    if ($dokumen->status == 'submitted') {
                                        $badgeClass = 'info';
                                        $statusText = 'Dikirim';
                                    } elseif ($dokumen->status == 'approved') {
                                        $badgeClass = 'success';
                                        $statusText = 'Disetujui';
                                    } elseif ($dokumen->status == 'rejected') {
                                        $badgeClass = 'danger';
                                        $statusText = 'Ditolak';
                                    } elseif ($dokumen->status == 'revisi') {
                                        $badgeClass = 'warning';
                                        $statusText = 'Revisi';
                                    } elseif ($dokumen->status == 'draft') {
                                        $badgeClass = 'warning';
                                        $statusText = 'Diproses (Draft)';
                                    }
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} px-3 py-2" style="font-size: 0.85rem; border-radius: 6px;">{{ $statusText }}</span>
                            </td>
                            <td class="text-center align-middle">
                                @if($dokumen->id)
                                    <button onclick="viewDocument('{{ route('guru.perangkat.print', $dokumen->id) }}')" class="btn btn-sm btn-primary font-weight-bold" style="border-radius: 6px;" title="Lihat Dokumen">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($dokumen->status == 'submitted')
                                        <form action="{{ route('kepala.dokumen.updateStatus', $dokumen->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="terima">
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold" style="border-radius: 6px;" title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button onclick="openRevisiModal({{ $dokumen->id }})" class="btn btn-sm btn-warning text-dark font-weight-bold" style="border-radius: 6px;" title="Revisi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-secondary font-weight-bold" style="border-radius: 6px;" disabled>
                                        <i class="fas fa-eye-slash mr-1"></i> Belum Ada
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                <h5>Belum ada dokumen administrasi</h5>
                                <p>Guru ini belum mengunggah atau membuat dokumen administrasi apapun.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card card-info card-outline mt-4 shadow-sm">
    <div class="card-header border-0">
        <h3 class="card-title font-weight-bold"><i class="fas fa-running mr-2 text-info"></i> Riwayat Kegiatan Guru</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive p-3">
            <table id="kegiatanTable" class="table table-hover m-0 align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Judul Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Periode</th>
                        <th class="text-center">Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatans as $kegiatan)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle"><strong>{{ $kegiatan->judul }}</strong></td>
                            <td class="align-middle text-muted">{{ \Str::limit($kegiatan->deskripsi, 60) }}</td>
                            <td class="align-middle">
                                {{ $kegiatan->tahun_ajaran }} <br>
                                <small class="text-muted">Semester {{ ucfirst($kegiatan->semester) }}</small>
                            </td>
                            <td class="text-center align-middle">
                                @if($kegiatan->foto_kegiatan)
                                    <a href="{{ asset('storage/' . $kegiatan->foto_kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold" style="border-radius: 6px;" title="Lihat Foto Kegiatan">
                                        <i class="fas fa-image mr-1"></i> Foto
                                    </a>
                                @endif
                                @if($kegiatan->sertifikat)
                                    <a href="{{ asset('storage/' . $kegiatan->sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold ml-1" style="border-radius: 6px;" title="Lihat Sertifikat">
                                        <i class="fas fa-certificate mr-1"></i> Sertifikat
                                    </a>
                                @endif
                                @if(!$kegiatan->foto_kegiatan && !$kegiatan->sertifikat)
                                    <span class="badge badge-light text-muted px-2 py-1 border">Tidak ada lampiran</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                                <h5>Belum ada kegiatan</h5>
                                <p>Guru ini belum menginputkan riwayat kegiatannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Modal Document Viewer -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lihat Dokumen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0" style="height: 80vh;">
        <iframe id="documentIframe" src="" frameborder="0" style="width: 100%; height: 100%;"></iframe>
      </div>
    </div>
  </div>
</div>

<!-- Modal Revisi -->
<div class="modal fade" id="revisiModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="revisiForm" method="POST">
      @csrf
      <input type="hidden" name="action" value="revisi">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Catatan Revisi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Masukkan catatan perbaikan:</label>
            <textarea name="catatan_revisi" class="form-control" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning text-dark font-weight-bold">Kirim Revisi</button>
        </div>
      </div>
    </form>
  </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
<style>
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #495057;
        letter-spacing: 0.5px;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.2s ease;
    }
    .align-middle {
        vertical-align: middle !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dokumenTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });

        $('#kegiatanTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });
    });

    function viewDocument(url) {
        $('#documentIframe').attr('src', url);
        $('#documentModal').modal('show');
    }

    function openRevisiModal(dokumenId) {
        let actionUrl = "{{ url('/kepala/dokumen') }}/" + dokumenId + "/update-status";
        $('#revisiForm').attr('action', actionUrl);
        $('#revisiModal').modal('show');
    }
</script>
@stop
