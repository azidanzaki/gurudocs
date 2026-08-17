@forelse($gurus as $guru)
    @php
        $pgs = $pgsByUser->get($guru->id, collect());
    @endphp
    <tr>
        <td class="align-middle px-4 font-weight-bold text-dark">
            {{ $guru->name }}
        </td>
        <td class="align-middle py-3">
            @if($pgs->isEmpty())
                <span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 4px;">Belum Dibuat</span>
            @else
                @foreach($pgs as $pg)
                    <div class="mb-2 bg-light p-2 rounded border">
                        <small class="font-weight-bold text-dark d-block mb-1">
                            {{ $pg->mapel->nama_mapel ?? '-' }} / {{ $pg->kelas->nama_kelas_simple ?? '-' }}
                        </small>
                        @if($pg->status == 'submitted' || $pg->is_completed)
                            <span class="badge badge-success px-2 py-1" style="border-radius: 4px;"><i class="fas fa-check-circle mr-1"></i> Disubmit</span>
                        @elseif($pg->status == 'draft')
                            <span class="badge badge-warning px-2 py-1" style="border-radius: 4px;"><i class="fas fa-edit mr-1"></i> Draft</span>
                        @else
                            <span class="badge badge-secondary px-2 py-1" style="border-radius: 4px;">{{ $pg->status }}</span>
                        @endif
                    </div>
                @endforeach
            @endif
        </td>
        <td class="align-middle text-center py-3">
            @foreach($pgs as $pg)
                @if($pg->status == 'submitted' || $pg->is_completed)
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('guru.perangkat.print', $pg->id) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary mr-1" style="border-radius: 6px; padding: 2px 8px; font-size: 12px;" title="Lihat PDF">
                            <i class="fas fa-file-pdf"></i> Lihat
                        </a>
                        <form action="{{ route('admin.kelolaperangkat.reopen', $pg->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-sm btn-outline-warning" style="border-radius: 6px; padding: 2px 8px; font-size: 12px;"
                                title="Buka kembali untuk revisi" onclick="event.preventDefault(); Swal.fire({title: 'Buka kembali?', text: 'Buka kembali perangkat ini agar guru bisa melakukan revisi?', icon: 'question', showCancelButton: true, confirmButtonColor: '#ffc107', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, buka kembali!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                <i class="fas fa-unlock"></i> Revisi
                            </button>
                        </form>
                    </div>
                @endif
            @endforeach
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center py-4 text-muted">
            <i class="fas fa-users-slash fa-2x mb-2 opacity-25"></i>
            <p class="mb-0">Tidak ada guru yang ditemukan.</p>
        </td>
    </tr>
@endforelse
