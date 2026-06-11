<div style="font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000;">

    {{-- KOP DAN JUDUL (TIDAK BISA DIEDIT) --}}
    <div class="mceNonEditable" style="background:#306D29; padding:12px; color: #f8f8f8ff; margin-bottom: 20px;">
        <div style="text-align: center;">
            <p style="margin: 0;"><strong>PROGRAM SEMESTER</strong></p>
            <p style="margin: 0;"><strong>MATA PELAJARAN {{ strtoupper($mapel->nama_mapel ?? '<<mata_pelajaran>>') }}</strong></p>
        </div>
    </div>

    {{-- ISI DOKUMEN (BISA DIEDIT) --}}
    <style>
        .mceEditable table, .mceEditable th, .mceEditable td {border: 1px solid black; border-collapse: collapse; padding: 5px;}
        .signature-table, .signature-table th, .signature-table td {border: none !important; border-collapse: collapse;}
    </style>

    <div class="mceEditable">
        <p><strong>Nama Madrasah    :   MTsN 3 Rokan Hulu</strong></p>
        <p><strong>Nama Penyusun    :   {{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<<nama_guru>>' }}</strong></p>
        <p><strong>Mata Pelajaran   :   {{ $mapel->nama_mapel ?? '<<mata_pelajaran>>' }}</strong></p>
        <p><strong>Kelas / Fase / Semester  :   {{ $kelas->nama_kelas ?? '<<kelas>>' }} / D / {{ $perangkatGuru->semester ?? '<<semester>>' }}</strong></p>
        <p><strong>Tahun Penyusunan :   {{ $perangkatGuru->tahun_ajaran ?? '<<tahun_pelajaran>>' }}</strong></p>

        <p><strong>CAPAIAN PEMBELAJARAN {{ strtoupper($mapel->nama_mapel ?? '<<mata_pelajaran>>') }}</strong></p>
        <p>&lt;&lt;Isi Capaian Pembelajaran...&gt;&gt;</p>

        <table style="width: 100%;">
            <tr>
                <td style="width: 30%;"><strong>Elemen</strong></td>
                <td><strong>Capaian Pembelajaran</strong></td>
            </tr>
            <tr>
                <td><br></td>
                <td><br></td>
            </tr>
        </table>
        <br>

        <table style="width: 100%; text-align: center;">
            <tr>
                <td rowspan="2"><strong>No</strong></td>
                <td rowspan="2"><strong>Tujuan Pembelajaran</strong></td>
                <td rowspan="2"><strong>Alokasi Waktu</strong></td>
                <td colspan="5"><strong>&lt;&lt;Juli&gt;&gt;</strong></td>
                <td colspan="5"><strong>&lt;&lt;Agustus&gt;&gt;</strong></td>
                <td colspan="5"><strong>&lt;&lt;September&gt;&gt;</strong></td>
                <td colspan="5"><strong>&lt;&lt;Oktober&gt;&gt;</strong></td>
                <td colspan="5"><strong>&lt;&lt;November&gt;&gt;</strong></td>
                <td colspan="5"><strong>&lt;&lt;Desember&gt;&gt;</strong></td>
            </tr>
            <tr>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
                <td><strong>1</strong></td><td><strong>2</strong></td><td><strong>3</strong></td><td><strong>4</strong></td><td><strong>5</strong></td>
            </tr>
            <tr>
                <td>1</td>
                <td></td>
                <td>0 JP</td>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>JUMLAH JAM PELAJARAN</strong></td>
                <td><strong>0 JP</strong></td>
                <td colspan="30"></td>
            </tr>
        </table>
    </div>

    {{-- TANDA TANGAN (BISA DIEDIT) --}}
    <div class="mceEditable" style="margin-top: 50px;">
        <table class="signature-table" style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0;">Kepala MTsN 3 Rokan Hulu</p>
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">&lt;&lt;kepala_sekolah&gt;&gt;</p>
                    <p style="margin: 0;">NIP. &lt;&lt;nip_kepalasekolah&gt;&gt;</p>
                </td>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <p style="margin: 0;">Pasir Pengaraian, ... &lt;&lt;Bulan&gt;&gt; 20...</p>
                    <p style="margin: 0;">Guru Mata Pelajaran</p>
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<<nama_guru>>' }}</p>
                    <p style="margin: 0;">NIP. &lt;&lt;nip_guru&gt;&gt;</p>
                </td>
            </tr>
        </table>
    </div>

</div>
