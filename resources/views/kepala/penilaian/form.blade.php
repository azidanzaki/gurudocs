@extends('adminlte::page')

@section('title', 'Formulir Penilaian Kinerja Guru')

@section('content_header')
    <h1>Penilaian Kinerja Guru - Aspek {{ $aspect }}</h1>
@stop

@section('content')
<div class="mb-3">
    <a href="{{ route('kepala.penilaian.pkg', ['id' => $guru->id, 'mapel_id' => $mapel ? $mapel->id : '']) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0" style="border-radius: 10px;">
    <div class="card-header bg-white border-bottom p-4">
        <h4 class="mb-2 font-weight-bold text-primary">
            Penilaian: {{ $guru->name }}
        </h4>
        <p class="text-muted mb-0">Mata Pelajaran: {{ $mapel ? $mapel->nama_mapel : 'Semua Mapel' }} | Tahun Ajaran: {{ $tahunAjaran ? $tahunAjaran->nama : '-' }}</p>
    </div>
    
    <div class="card-body bg-light p-4">
        <div class="alert alert-info shadow-sm" style="border-radius: 8px;">
            <h5 class="font-weight-bold"><i class="fas fa-info-circle mr-2"></i> Pedoman Penilaian</h5>
            <ul class="mb-0 pl-3">
                <li><strong>1 = Sangat Buruk / Sangat Tidak Puas:</strong> Kualitas sangat jauh di bawah harapan.</li>
                <li><strong>2 = Buruk / Tidak Puas:</strong> Kualitas di bawah harapan dan perlu banyak perbaikan.</li>
                <li><strong>3 = Cukup / Biasa Saja:</strong> Kualitas standar, memenuhi harapan dasar tetapi tidak istimewa.</li>
                <li><strong>4 = Baik / Puas:</strong> Kualitas bagus dan memenuhi harapan.</li>
                <li><strong>5 = Sangat Baik / Sangat Puas:</strong> Kualitas luar biasa, melebihi ekspektasi.</li>
            </ul>
        </div>

        <form id="penilaianForm" action="{{ route('kepala.penilaian.store', ['id' => $guru->id, 'aspect' => $aspect]) }}" method="POST">
            @csrf
            <input type="hidden" name="mapel_id" value="{{ $mapel ? $mapel->id : '' }}">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran ? $tahunAjaran->id : '' }}">
            <input type="hidden" name="action" id="formAction" value="draft">

            @php
                $isSubmitted = $penilaian && $penilaian->status === 'submitted';
                $data = $penilaian ? $penilaian->data_penilaian : [];
            @endphp

            @if(empty($indicators))
                <div class="alert alert-warning">
                    Formulir untuk Aspek ini belum tersedia.
                </div>
            @else
                @foreach($indicators as $category => $items)
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 font-weight-bold">{{ $category }}</h5>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%;" class="align-middle">Indikator</th>
                                        <th class="text-center align-middle" style="width: 10%;">1</th>
                                        <th class="text-center align-middle" style="width: 10%;">2</th>
                                        <th class="text-center align-middle" style="width: 10%;">3</th>
                                        <th class="text-center align-middle" style="width: 10%;">4</th>
                                        <th class="text-center align-middle" style="width: 10%;">5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $idx => $item)
                                        @php
                                            $val = isset($data[$category][$item]) ? $data[$category][$item] : '';
                                        @endphp
                                        <tr>
                                            <td class="align-middle" style="font-size: 1.05rem;">{{ $item }}</td>
                                            @for($score = 1; $score <= 5; $score++)
                                                <td class="text-center align-middle">
                                                    <input type="radio" name="penilaian[{{ $category }}][{{ $item }}]" value="{{ $score }}" class="score-input" {{ $val == $score ? 'checked' : '' }} {{ $isSubmitted ? 'disabled' : '' }} required style="cursor:pointer; transform: scale(1.1);">
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

            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold">Komentar / Saran</h5>
                </div>
                <div class="card-body">
                    <textarea name="catatan" class="form-control" rows="4" placeholder="Masukkan komentar atau saran untuk guru di sini..." {{ $isSubmitted ? 'readonly' : '' }}>{{ $penilaian ? $penilaian->catatan : '' }}</textarea>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
                            <h4 class="font-weight-bold mb-0">Total Skor: <span id="totalSkorDisplay" class="text-primary">{{ $penilaian ? $penilaian->total_skor : '0' }}</span></h4>
                        </div>
                        <div class="col-md-6 text-center text-md-right">
                            <h4 class="font-weight-bold mb-0">Rata-rata: <span id="rataRataDisplay" class="text-success">{{ $penilaian ? number_format($penilaian->rata_rata, 2) : '0.00' }}</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                @if($isSubmitted)
                    <button type="button" class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-lock mr-2"></i> Penilaian Sudah Selesai
                    </button>
                @else
                    <button type="button" id="btnDraft" class="btn btn-warning btn-lg text-dark font-weight-bold px-4">
                        <i class="fas fa-save mr-2"></i> Simpan Draft
                    </button>
                    <button type="button" id="btnSubmit" class="btn btn-success btn-lg font-weight-bold px-4">
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
                    confirmButtonColor: '#007bff'
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
