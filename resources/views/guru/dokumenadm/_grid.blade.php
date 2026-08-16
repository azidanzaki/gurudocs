@forelse ($dokumen as $item)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{ route('guru.dokumenadmguru.show', $item->id) }}" class="text-decoration-none text-dark d-block h-100">
            <div class="card document-card h-100">
                <div class="document-preview text-center p-3 d-flex align-items-center justify-content-center bg-light">
                    @php
                        $wordExt = $item->file_word ? pathinfo($item->file_word, PATHINFO_EXTENSION) : '';
                        $isExcel = in_array($wordExt, ['xls', 'xlsx']);
                    @endphp

                    @if($item->file_pdf)
                        <canvas class="pdf-thumbnail" data-pdf-url="{{ asset('storage/' . $item->file_pdf) }}" style="max-width: 100%; max-height: 100%; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;"></canvas>
                        <div class="pdf-loading-spinner spinner-border spinner-border-sm text-primary" role="status" style="position: absolute;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }} pdf-fallback-icon" style="font-size: 70px; display: none;"></i>
                    @else
                        <i class="fas {{ $isExcel ? 'fa-file-excel text-success' : 'fa-file-word text-primary' }}" style="font-size: 70px; opacity: 0.9;"></i>
                    @endif
                </div>
                <div class="card-body bg-white d-flex flex-column justify-content-between p-3">
                    <p class="mb-2 font-weight-bold text-dark" style="font-size: 1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->judul }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="badge badge-light border text-muted px-2 py-1" style="border-radius: 6px; font-size: 0.75rem;">
                            {{ $item->tahun ?? date('Y') }}
                        </span>
                        <span class="text-primary small font-weight-bold">
                            Buka <i class="fas fa-arrow-right ml-1"></i>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-12 text-center py-5 mt-4 w-100">
        <i class="fas fa-folder-open text-muted fa-4x mb-4 opacity-25"></i>
        <h4 class="text-muted font-weight-bold">Tidak Ada Dokumen</h4>
        <p class="text-muted">Belum ada template dokumen yang tersedia di kategori ini.</p>
    </div>
@endforelse
