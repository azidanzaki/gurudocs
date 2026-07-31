<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penilaian Kinerja Guru</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
        }
        h2 {
            text-align: center;
            font-size: 16pt;
            margin-bottom: 5px;
        }
        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }
        .header-table td {
            padding: 3px;
            vertical-align: top;
        }
        .header-label {
            width: 150px;
        }
        .header-colon {
            width: 10px;
        }
        
        table.eval-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        table.eval-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        table.eval-table th, table.eval-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }
        table.eval-table th {
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        
        .aspect-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        
        .checkbox-cell {
            text-align: center;
            font-size: 14pt;
        }

        .summary-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 20px;
            width: 300px;
            float: right;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <h2>Penilaian Kinerja Guru</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <strong>Dalam Perencanaan, Pelaksanaan Pembelajaran, Membuka/Menutup,<br/>Variasi Stimulus, Bertanya, dan Memberikan Penguatan</strong>
    </div>

    <table class="header-table">
        <tr>
            <td class="header-label">Nama Guru</td>
            <td class="header-colon">:</td>
            <td>{{ $guru_name }}</td>
        </tr>
        <tr>
            <td class="header-label">NIP</td>
            <td class="header-colon">:</td>
            <td>{{ $guru_nip }}</td>
        </tr>
        <tr>
            <td class="header-label">Mata Pelajaran</td>
            <td class="header-colon">:</td>
            <td>{{ $mapel }}</td>
        </tr>
        <tr>
            <td class="header-label">Tahun Ajaran</td>
            <td class="header-colon">:</td>
            <td>{{ $tahun_ajaran }}</td>
        </tr>
        <tr>
            <td class="header-label">Penilai</td>
            <td class="header-colon">:</td>
            <td>{{ $penilai }}</td>
        </tr>
    </table>

    @foreach($aspectsData as $aspectId => $data)
        @php
            $aspectNames = [
                1 => 'Perencanaan Pembelajaran',
                2 => 'Pelaksanaan Pembelajaran',
                3 => 'Membuka dan Menutup Pembelajaran',
                4 => 'Pelaksanaan Variasi Pembelajaran',
                5 => 'Keterampilan Bertanya',
                6 => 'Memberikan Penguatan',
                7 => 'Menutup Pembelajaran'
            ];
            $aspectTitle = $aspectNames[$aspectId] ?? 'Aspek ' . $aspectId;
            $penilaianScores = $data['penilaian']->data_penilaian ?? [];
        @endphp

        <div class="aspect-title">{{ $aspectId }}. {{ $aspectTitle }}</div>
        
        <table class="eval-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">No</th>
                    <th rowspan="2" style="width: 65%;">Indikator Penilaian</th>
                    <th colspan="5" style="width: 30%;">Skor</th>
                </tr>
                <tr>
                    <th style="width: 6%;">1</th>
                    <th style="width: 6%;">2</th>
                    <th style="width: 6%;">3</th>
                    <th style="width: 6%;">4</th>
                    <th style="width: 6%;">5</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($data['indicators'] as $category => $items)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td colspan="6" style="font-weight: bold; background-color: #f9f9f9;">{{ $category }}</td>
                    </tr>
                    @foreach($items as $item)
                        @php
                            $score = $penilaianScores[$category][$item] ?? null;
                        @endphp
                        <tr>
                            <td></td>
                            <td>{{ $item }}</td>
                            <td class="checkbox-cell">{!! $score == 1 ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span>' : '' !!}</td>
                            <td class="checkbox-cell">{!! $score == 2 ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span>' : '' !!}</td>
                            <td class="checkbox-cell">{!! $score == 3 ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span>' : '' !!}</td>
                            <td class="checkbox-cell">{!! $score == 4 ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span>' : '' !!}</td>
                            <td class="checkbox-cell">{!! $score == 5 ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10004;</span>' : '' !!}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        
        <div style="text-align: right; margin-bottom: 30px;">
            <strong>Rata-rata Aspek {{ $aspectId }}: {{ number_format($data['penilaian']->rata_rata ?? 0, 2) }}</strong>
        </div>
    @endforeach

    <br><br>
    
    <table style="width: 100%; border: none; margin-top: 30px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br/>
                Guru yang dinilai,
                <br/><br/><br/><br/>
                <strong><u>{{ $guru_name }}</u></strong>
                <br/>NIP. {{ $guru_nip ?: '-' }}
            </td>
            <td style="width: 50%; text-align: center;">
                Mengetahui,<br/>
                Kepala Sekolah,
                <br/><br/><br/><br/>
                <strong><u>{{ $penilai }}</u></strong>
                <br/>NIP. .............................
            </td>
        </tr>
    </table>

</body>
</html>
