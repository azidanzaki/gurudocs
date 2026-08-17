@extends('adminlte::page')

@section('title', 'Formulir Penilaian Kinerja Guru')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="font-weight-bold text-dark m-0">Penilaian Kinerja - Aspek {{ $aspect }}</h1>
    <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel ? $mapel->id : '', 'kelas_id' => $kelas ? $kelas->id : '', 'tahun_ajaran_id' => $selectedTahunId]) }}" class="btn btn-outline-secondary btn-sm shadow-sm" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden; margin-bottom: 24px;">
    <div class="card-header bg-white border-bottom-0 p-4">
        <h4 class="mb-1 font-weight-bold text-dark" style="font-size: 1.3rem;">
            Penilaian Evaluasi: {{ $guru->name }}
        </h4>
        <div class="d-flex align-items-center mt-2 flex-wrap gap-2 text-muted" style="font-size: 0.9rem;">
            <span class="mr-3"><i class="fas fa-book mr-1 text-primary"></i> {{ $mapel ? $mapel->nama_mapel : 'Semua Mapel' }}</span>
            <span class="mr-3"><i class="fas fa-chalkboard mr-1"></i> Kelas {{ $kelas ? $kelas->nama_kelas : 'Semua Kelas' }}</span>
            <span><i class="fas fa-calendar-alt mr-1"></i> TA {{ $selectedTahunObj ? $selectedTahunObj->nama : '-' }}</span>
        </div>
    </div>
    
    <div class="card-body bg-light p-4">
        <div class="alert alert-info border-0 shadow-sm mb-4 p-4" style="border-radius: 12px; background-color: #ebf8ff; color: #2b6cb0;">
            <h5 class="font-weight-bold mb-3"><i class="fas fa-info-circle mr-2"></i> Pedoman Penilaian Kinerja</h5>
            <div class="row" style="font-size: 0.95rem;">
                <div class="col-md-6 mb-2"><strong>1 = Sangat Buruk / Sangat Tidak Puas:</strong> Kualitas sangat jauh di bawah harapan.</div>
                <div class="col-md-6 mb-2"><strong>2 = Buruk / Tidak Puas:</strong> Kualitas di bawah harapan dan perlu banyak perbaikan.</div>
                <div class="col-md-6 mb-2"><strong>3 = Cukup / Biasa Saja:</strong> Kualitas standar, memenuhi harapan dasar.</div>
                <div class="col-md-6 mb-2"><strong>4 = Baik / Puas:</strong> Kualitas bagus dan memenuhi harapan.</div>
                <div class="col-md-6 mb-2"><strong>5 = Sangat Baik / Sangat Puas:</strong> Kualitas luar biasa, melebihi ekspektasi.</div>
            </div>
        </div>

        <form id="penilaianForm" action="{{ route('kepala.penilaian.store', ['id' => $guru->id, 'aspect' => $aspect]) }}" method="POST">
            @csrf
            <input type="hidden" name="mapel_id" value="{{ $mapel ? $mapel->id : '' }}">
            <input type="hidden" name="kelas_id" value="{{ $kelas ? $kelas->id : '' }}">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunId }}">
            <input type="hidden" name="action" id="formAction" value="draft">

            @php
                $isSubmitted = $penilaian && $penilaian->status === 'submitted';
                $data = $penilaian ? $penilaian->data_penilaian : [];
            @endphp

            @if(empty($indicators))
                <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Formulir untuk Aspek ini belum tersedia.
                </div>
            @else
                @foreach($indicators as $category => $items)
                    <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header border-0 py-3 text-white" style="background: linear-gradient(135deg, #4f46e5, #06b6d4);">
                            <h5 class="mb-0 font-weight-bold" style="font-size: 1.05rem;"><i class="fas fa-folder mr-2"></i> {{ $category }}</h5>
                        </div>
                        <div class="card-body p-0 table-responsive bg-white">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%;" class="align-middle px-4 py-3 border-0">Indikator Kinerja</th>
                                        <th class="text-center align-middle border-0" style="width: 10%;">1</th>
                                        <th class="text-center align-middle border-0" style="width: 10%;">2</th>
                                        <th class="text-center align-middle border-0" style="width: 10%;">3</th>
                                        <th class="text-center align-middle border-0" style="width: 10%;">4</th>
                                        <th class="text-center align-middle border-0" style="width: 10%;">5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $idx => $item)
                                        @php
                                            $val = isset($data[$category][$item]) ? $data[$category][$item] : '';
                                        @endphp
                                        <tr>
                                            <td class="align-middle px-4 py-3 font-weight-500" style="font-size: 0.98rem; color: #2d3748;">{{ $item }}</td>
                                            @for($score = 1; $score <= 5; $score++)
                                                <td class="text-center align-middle py-3">
                                                    <input type="radio" name="penilaian[{{ $category }}][{{ $item }}]" value="{{ $score }}" class="score-input" {{ $val == $score ? 'checked' : '' }} {{ $isSubmitted ? 'disabled' : '' }} required>
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header border-0 py-3 bg-light text-dark border-bottom">
                    <h5 class="mb-0 font-weight-bold" style="font-size: 1.05rem;"><i class="fas fa-comment-dots mr-2 text-primary"></i> Komentar / Catatan Evaluasi</h5>
                </div>
                <div class="card-body">
                    <textarea name="catatan" class="form-control border-0 bg-light p-3" rows="4" placeholder="Masukkan komentar, saran, atau catatan evaluasi untuk guru di sini..." style="border-radius: 8px; resize: none;" {{ $isSubmitted ? 'readonly' : '' }}>{{ $penilaian ? $penilaian->catatan : '' }}</textarea>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <h4 class="font-weight-bold mb-0 text-dark" style="font-size: 1.25rem;">Total Skor Perolehan: <span id="totalSkorDisplay" class="text-primary ml-1" style="font-size: 1.6rem;">{{ $penilaian ? $penilaian->total_skor : '0' }}</span></h4>
                        </div>
                        <div class="col-md-6 text-center text-md-right">
                            <h4 class="font-weight-bold mb-0 text-dark" style="font-size: 1.25rem;">Rata-rata Nilai: <span id="rataRataDisplay" class="text-success ml-1" style="font-size: 1.6rem;">{{ $penilaian ? number_format($penilaian->rata_rata, 2) : '0.00' }}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4 flex-wrap gap-3">
                @if($isSubmitted)
                    <button type="button" class="btn btn-secondary btn-lg shadow-sm w-100 d-flex align-items-center justify-content-center" disabled style="border-radius: 12px; height: 50px;">
                        <i class="fas fa-lock mr-2"></i> Penilaian Sudah Selesai & Terkunci
                    </button>
                @else
                    <button type="button" id="btnDraft" class="btn btn-warning btn-lg text-dark font-weight-bold px-4 shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 12px; height: 50px; min-width: 180px;">
                        <i class="fas fa-save mr-2"></i> Simpan Draft
                    </button>
                    <button type="button" id="btnSubmit" class="btn btn-success btn-lg font-weight-bold px-4 shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 12px; height: 50px; min-width: 220px;">
                        <i class="fas fa-paper-plane mr-2"></i> Selesaikan Penilaian
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 0.75rem; }
    
    /* Radio Button Custom Styling */
    .score-input {
        appearance: none;
        -webkit-appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #cbd5e0;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        position: relative;
    }
    .score-input:hover {
        border-color: #4f46e5;
    }
    .score-input:checked {
        border-color: #4f46e5;
        background-color: #4f46e5;
    }
    .score-input:checked::before {
        content: "";
        width: 8px;
        height: 8px;
        background-color: white;
        border-radius: 50%;
        display: block;
    }
    .score-input:disabled {
        background-color: #e2e8f0;
        border-color: #cbd5e0;
        cursor: not-allowed;
    }
    .score-input:disabled:checked::before {
        background-color: #a0aec0;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        function calculateScore() {
            let total = 0;
            let count = 0;
            $('.score-input:checked').each(function() {
                total += parseInt($(this).val());
                count++;
            });
            let avg = count > 0 ? (total / count) : 0;
            
            $('#totalSkorDisplay').text(total);
            $('#rataRataDisplay').text(avg.toFixed(2));
        }

        // Trigger on change
        $('.score-input').change(function() {
            calculateScore();
        });

        $('#btnDraft').click(function() {
            let btn = $(this);
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
            btn.prop('disabled', true);
            $('#btnSubmit').prop('disabled', true);
            
            $('#formAction').val('draft');
            
            // Allow submission without required fields for draft
            $('.score-input').removeAttr('required');
            
            $('#penilaianForm').submit();
        });

        $('#btnSubmit').click(function() {
            // Check required fields
            let allFilled = true;
            let totalQuestions = $('.score-input').length / 5;
            let answered = $('.score-input:checked').length;
            
            if(answered < totalQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Lengkap',
                    text: 'Harap isi semua penilaian sebelum menyelesaikan form!',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            Swal.fire({
                title: 'Selesaikan Penilaian?',
                text: "Setelah disimpan, formulir ini tidak dapat diubah lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let btn = $(this);
                    btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                    btn.prop('disabled', true);
                    $('#btnDraft').prop('disabled', true);
                    
                    $('#formAction').val('submit');
                    $('#penilaianForm').submit();
                }
            });
        });
    });
</script>
@stop
