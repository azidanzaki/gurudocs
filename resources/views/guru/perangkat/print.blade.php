<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $perangkatGuru->template->nama_perangkat }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
            padding: 2cm;
            max-width: 21cm;
            margin: 0 auto;
            line-height: 1.5;
        }
        /* Restore basic typography margins inside wysiwyg content */
        p { margin-bottom: 1em; }
        h1, h2, h3, h4, h5, h6 { margin-bottom: 0.5em; margin-top: 1em; }
        .doc-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .doc-sub {
            text-align: center;
            font-size: 11pt;
            color: #444;
            margin-bottom: 20px;
        }
        hr { border: 1px solid #000; margin: 16px 0; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .info-table td:first-child { font-weight: bold; width: 160px; }

        .section-label {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 4px;
            border-left: 4px solid #000;
            padding-left: 8px;
        }
        .section-value {
            padding: 8px;
            min-height: 32px;
            border-bottom: 1px solid #ccc;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        .signature-area {
            margin-top: 48px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box { text-align: center; width: 200px; }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 64px;
            margin-bottom: 4px;
        }

        .print-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 24px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14pt;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; }
            .mceEditable { border: none !important; }
        }
    </style>
</head>
<body>

    @if($perangkatGuru->template_id == 3)
        {{-- Custom Layout Khusus Capaian Pembelajaran (CP) - 1 WYSIWYG Besar --}}
        <div>
            @foreach($perangkatGuru->template->sections as $section)
                <div>
                    {!! $savedValues[$section->field_key] ?? '' !!}
                </div>
            @endforeach
        </div>
    @else
        {{-- Generic Layout untuk Perangkat Lainnya --}}
        <div class="doc-title">{{ strtoupper($perangkatGuru->template->nama_perangkat) }}</div>
        <div class="doc-sub">{{ $perangkatGuru->mapel->nama_mapel }} &mdash; Kelas {{ $perangkatGuru->kelas->nama_kelas }}</div>

        <hr>

        <table class="info-table">
            <tr>
                <td>Mata Pelajaran</td>
                <td>: {{ $perangkatGuru->mapel->nama_mapel }}</td>
                <td>Tahun Ajaran</td>
                <td>: {{ $perangkatGuru->tahun_ajaran }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: {{ $perangkatGuru->kelas->nama_kelas }}</td>
                <td>Semester</td>
                <td>: {{ $perangkatGuru->semester }}</td>
            </tr>
            <tr>
                <td>Guru</td>
                <td colspan="3">: {{ $perangkatGuru->guru->name }}</td>
            </tr>
        </table>

        <hr>

        @foreach($perangkatGuru->template->sections as $section)
            <div class="section-label">{{ $section->label }}</div>
            <div class="section-value" style="{{ $section->field_type === 'richtext' ? 'border:none; padding:0;' : '' }}">
                @if($section->field_type === 'richtext')
                    {!! $savedValues[$section->field_key] ?? '' !!}
                @else
                    {!! nl2br(e($savedValues[$section->field_key] ?? '')) !!}
                @endif
            </div>
        @endforeach

        <div class="signature-area">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <div class="signature-line"></div>
                <strong>{{ $perangkatGuru->guru->name }}</strong><br>
                <small>Guru {{ $perangkatGuru->mapel->nama_mapel }}</small>
            </div>
        </div>
    @endif

    <button class="print-btn" onclick="window.print()">
        &#128438; Cetak / Simpan PDF
    </button>

</body>
</html>