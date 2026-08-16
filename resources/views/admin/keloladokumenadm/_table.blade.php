@forelse($dokumen as $key => $item)
    <tr>
        <td class="px-4 py-3 align-middle">{{ $dokumen->firstItem() + $key }}</td>
        <td class="py-3 align-middle font-weight-bold text-dark">
            @php
                $ext = strtolower(pathinfo($item->file_word, PATHINFO_EXTENSION));
                $icon = 'fa-file-alt text-secondary';
                if (in_array($ext, ['doc', 'docx'])) $icon = 'fa-file-word text-primary';
                elseif (in_array($ext, ['xls', 'xlsx'])) $icon = 'fa-file-excel text-success';
                elseif ($ext == 'pdf') $icon = 'fa-file-pdf text-danger';
            @endphp
            <i class="fas {{ $icon }} fa-lg mr-2"></i> {{ $item->judul }}
        </td>
        <td class="py-3 align-middle text-center">
            <span class="badge badge-light border text-dark px-3 py-2" style="border-radius: 20px;">{{ $item->jenis_dokumen }}</span>
        </td>
        <td class="py-3 align-middle text-center font-weight-bold">{{ $item->tahun ?? '-' }}</td>
        <td class="py-3 align-middle text-center">
            @if ($item->file_pdf)
                <a href="{{ asset('storage/' . $item->file_pdf) }}" class="btn btn-outline-danger btn-sm px-3 mr-1" style="border-radius: 6px;" target="_blank" title="Lihat PDF">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
            @endif

            @if ($item->file_word)
                <a href="{{ asset('storage/' . $item->file_word) }}" class="btn btn-outline-success btn-sm px-3 mr-1" style="border-radius: 6px;" title="Unduh File">
                    <i class="fas fa-download mr-1"></i> File Asli
                </a>
            @endif

            <form action="{{ route('admin.dokumenadm.delete', $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;"
                    onclick="event.preventDefault(); Swal.fire({title: 'Hapus dokumen ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
            <p class="mb-0">Belum ada template dokumen yang diunggah.</p>
        </td>
    </tr>
@endforelse

<tr class="pagination-row d-none">
    <td colspan="5">
        @if($dokumen->hasPages())
            <div class="d-flex justify-content-center mt-3" id="pagination-links">
                {{ $dokumen->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </td>
</tr>
