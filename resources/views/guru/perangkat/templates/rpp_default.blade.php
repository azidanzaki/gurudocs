<div style="font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; color: #000;">
    <div class="mceNonEditable" style="background:#306D29; padding:12px; color: #f8f8f8ff;">
        <div style="text-align: center;">
            <p style="margin: 0;"><strong>MODUL AJAR / RPP</strong></p>
            <p style="margin: 0;"><strong>{{ strtoupper($mapel->nama_mapel ?? '<mata_pelajaran>') }}</strong></p>
        </div>
    </div>
    <style>
        .mceEditable table, .mceEditable th, .mceEditable td {border: none !important; border-collapse: collapse;}
    </style>
    <div class="mceEditable">
        <p><strong>Informasi Umum</strong></p>
        {!! html_entity_decode($savedValues['informasi_umum'] ?? '—') !!}
        <p><strong>Kompetensi Inti</strong></p>
        {!! html_entity_decode($savedValues['kompetensi_inti'] ?? '—') !!}
        <p><strong>Langkah-langkah Pembelajaran</strong></p>
        {!! html_entity_decode($savedValues['langkah_pembelajaran'] ?? '—') !!}
        <p><strong>Asesmen / Penilaian</strong></p>
        {!! html_entity_decode($savedValues['asesmen'] ?? '—') !!}
        <p><strong>Lampiran</strong></p>
        {!! html_entity_decode($savedValues['lampiran'] ?? '—') !!}
    </div>
    <div class="mceEditable" style="margin-top: 50px;">
        <table class="signature-table" style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0;">Kepala {{ $sekolah ?? 'Sekolah' }}</p>
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<nama_guru>' }}</p>
                    <p style="margin: 0;">NIP. {{ $perangkatGuru->guru->nip ?? '<nip_guru>' }}</p>
                </td>
                <td style="width: 50%; text-align: left; vertical-align: top;">
                    <p style="margin: 0;">{{ $lokasi ?? 'Lokasi' }}, {{ $tanggal ?? 'Tanggal' }}</p>
                    <p style="margin: 0;">Guru Mata Pelajaran</p>
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $perangkatGuru->guru->name ?? Auth::user()->name ?? '<nama_guru>' }}</p>
                    <p style="margin: 0;">NIP. {{ $perangkatGuru->guru->nip ?? '<nip_guru>' }}</p>
                </td>
            </tr>
        </table>
    </div>
</div>
