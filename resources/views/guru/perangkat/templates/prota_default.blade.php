<div style="font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000;">
    <div class="mceNonEditable" style="background:#306D29; padding:12px; color: #f8f8f8ff; margin-bottom: 20px;">
        <div style="text-align: center;">
            <p style="margin: 0;"><strong>PROGRAM TAHUNAN</strong></p>
            <p style="margin: 0;"><strong>MATA PELAJARAN {{ strtoupper($mapel->nama_mapel ?? '<<mata_pelajaran>>') }}</strong></p>
        </div>
    </div>

    <style>
        .mceEditable table, .mceEditable th, .mceEditable td {border: 1px solid black; border-collapse: collapse; padding: 5px;}
        .signature-table, .signature-table th, .signature-table td {border: none !important; border-collapse: collapse;}
    </style>

    <div class="mceEditable">
        <p><strong>Nama Madrasah    :   MTsN 3 Rokan Hulu</strong></p>
        <p><strong>Nama Penyusun    :   {{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<<nama_guru>>' }}</strong></p>
        <p><strong>Mata Pelajaran   :   {{ $mapel->nama_mapel ?? '<<mata_pelajaran>>' }}</strong></p>
        <p><strong>Kelas            :   {{ $kelas->nama_kelas ?? '<<kelas>>' }}</strong></p>
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

        <table style="width: 100%;">
            <tr style="text-align: center;">
                <td style="width: 10%;"><strong>No</strong></td>
                <td style="width: 40%;"><strong>Tujuan Pembelajaran (TP)</strong></td>
                <td style="width: 30%;"><strong>Materi</strong></td>
                <td style="width: 20%;"><strong>Alokasi Waktu</strong></td>
            </tr>
            <tr>
                <td colspan="4"><strong>SEMESTER 1</strong></td>
            </tr>
            <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
            <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
            <tr>
                <td colspan="4"><strong>SEMESTER 2</strong></td>
            </tr>
            <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
            <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
            <tr>
                <td colspan="3"><strong>Cadangan Jam Pelajaran</strong></td>
                <td><strong>0 JP</strong></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Jumlah Jam Pelajaran</strong></td>
                <td><strong>0 JP</strong></td>
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
