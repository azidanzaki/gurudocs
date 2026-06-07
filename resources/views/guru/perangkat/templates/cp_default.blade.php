<div style="font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000;">

    {{-- KOP DAN JUDUL (TIDAK BISA DIEDIT) --}}
    <div class="mceNonEditable" style="background:#306D29; padding:12px; color: #f8f8f8ff;">
        <div style="text-align: center;">
            <p style="margin: 0;"><strong>CAPAIAN PEMBELAJARAN</strong></p>
            <p style="margin: 0;"><strong>MATA PELAJARAN {{ strtoupper($mapel->nama_mapel ?? '<<mata_pelajaran>>') }}</strong></p>
        </div>
    </div>

    {{-- ISI DOKUMEN (BISA DIEDIT) --}}
    <style>
.mceEditable table, .mceEditable th, .mceEditable td {border: none !important; border-collapse: collapse;}
</style>
        <p><strong>A. Rasional Mata Pelajaran {{ $mapel->nama_mapel ?? '<<MATA_PELAJARAN>>' }}</strong></p>
        <p>&lt;&lt;isi_rasional_cp&gt;&gt;</p>
        
        <p><strong>B. Tujuan Mata Pelajaran {{ $mapel->nama_mapel ?? '<<MATA_PELAJARAN>>' }}</strong></p>
        <p>&lt;&lt;isi_tujuan_cp&gt;&gt;</p>
        
        <p><strong>C. Karakteristik Mata Pelajaran {{ $mapel->nama_mapel ?? '<<MATA_PELAJARAN>>' }}</strong></p>
        <p>&lt;&lt;isi_karakteristik_cp&gt;&gt;</p>
        
        <p><strong>D. Elemen Mata Pelajaran {{ $mapel->nama_mapel ?? '<<MATA_PELAJARAN>>' }}</strong></p>
        <p>&lt;&lt;isi_elemen_cp&gt;&gt;</p>
    </div>

    {{-- TANDA TANGAN (BISA DIEDIT) --}}
    <div class="mceEditable" style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: left;">
                    <p style="margin: 0;">&lt;&lt;tempat&gt;&gt;, &lt;&lt;hari, tanggal, bulan, tahun&gt;&gt;</p>
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; font-family: 'Times New Roman', serif; text-decoration: underline;">{{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<<nama_guru>>' }}</p>
                    <p style="margin: 0; font-family: 'Times New Roman', serif;">Nip. &lt;&lt;nip&gt;&gt;</p>
                </td>
            </tr>
        </table>
    </div>

</div>
