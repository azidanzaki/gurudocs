@extends('adminlte::page')

@section('title', 'Cetak Penilaian Kinerja Guru')

@section('content_header')
    <h1>Cetak Penilaian Kinerja Guru</h1>
@stop

@section('content')
    <div class="card shadow-sm border-0" style="border-radius: 10px;">
        <div class="card-body p-4">
            <h4 class="mb-3">Guru: {{ $guru_name }} {{ $guru_nip ? '(NIP: ' . $guru_nip . ')' : '' }}</h4>
            <p class="mb-1"><strong>Mapel:</strong> {{ $mapel }}</p>
            <p class="mb-1"><strong>Tahun Ajaran:</strong> {{ $tahun_ajaran }}</p>
            <p class="mb-3"><strong>Penilai:</strong> {{ $penilai }}</p>
            @foreach($aspects as $aspect)
                <div class="border mb-3 p-3" style="border-radius: 6px;">
                    <h5>Aspek {{ $aspect['aspek'] }}</h5>
                    <p><strong>Total Skor:</strong> {{ $aspect['total_skor'] }} | <strong>Rata‑Rata:</strong> {{ $aspect['rata_rata'] }}</p>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Indikator</th><th>Skor</th></tr>
                        </thead>
                        <tbody>
                        @foreach($aspect['data_penilaian'] as $category => $items)
                            @foreach($items as $item => $score)
                                <tr>
                                    <td>{{ $category }} - {{ $item }}</td>
                                    <td>{{ $score }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="window.print();"><i class="fas fa-print mr-1"></i> Print</button>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000; }
        .table th, .table td { padding: 0.3rem; }
    </style>
@stop
