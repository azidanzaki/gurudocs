<?php

namespace Database\Seeders;

use App\Models\PerangkatTemplate;
use App\Models\PerangkatTemplateSection;
use Illuminate\Database\Seeder;

class PerangkatTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'nama_perangkat' => 'Program Tahunan (Prota)',
                'deskripsi'      => 'Disusun 1 kali setahun',
                'urutan'         => 1,
                'frekuensi'      => 'tahunan',
                'sections'       => [
                    ['label' => 'Capaian Pembelajaran', 'field_key' => 'cp', 'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Tujuan Pembelajaran', 'field_key' => 'tp', 'field_type' => 'textarea', 'urutan' => 2],
                    ['label' => 'Alokasi Waktu', 'field_key' => 'alokasi_waktu', 'field_type' => 'text', 'urutan' => 3],
                ],
            ],
            [
                'nama_perangkat' => 'Program Semester (Promes)',
                'deskripsi'      => 'Disusun 1 kali per semester (2x setahun)',
                'urutan'         => 2,
                'frekuensi'      => 'semesteran',
                'sections'       => [
                    ['label' => 'Tujuan Pembelajaran', 'field_key' => 'tp', 'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Materi Pokok', 'field_key' => 'materi_pokok', 'field_type' => 'textarea', 'urutan' => 2],
                    ['label' => 'Bulan & Minggu Ke', 'field_key' => 'waktu_pelaksanaan', 'field_type' => 'text', 'urutan' => 3],
                ],
            ],
            [
                'nama_perangkat' => 'Capaian Pembelajaran (CP)',
                'deskripsi'      => 'Disusun 1 kali setahun',
                'urutan'         => 3,
                'frekuensi'      => 'tahunan',
                'sections'       => [
                    ['label' => 'Fase', 'field_key' => 'fase', 'field_type' => 'text', 'urutan' => 1],
                    ['label' => 'Elemen', 'field_key' => 'elemen', 'field_type' => 'text', 'urutan' => 2],
                    ['label' => 'Deskripsi Capaian', 'field_key' => 'deskripsi_cp', 'field_type' => 'textarea', 'urutan' => 3],
                ],
            ],
            [
                'nama_perangkat' => 'Tujuan Pembelajaran (TP)',
                'deskripsi'      => 'Disusun 1 kali setahun',
                'urutan'         => 4,
                'frekuensi'      => 'tahunan',
                'sections'       => [
                    ['label' => 'Capaian Pembelajaran', 'field_key' => 'cp', 'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Kompetensi', 'field_key' => 'kompetensi', 'field_type' => 'text', 'urutan' => 2],
                    ['label' => 'Konten/Materi', 'field_key' => 'konten', 'field_type' => 'text', 'urutan' => 3],
                    ['label' => 'Rumusan TP', 'field_key' => 'rumusan_tp', 'field_type' => 'textarea', 'urutan' => 4],
                ],
            ],
            [
                'nama_perangkat' => 'Alur Tujuan Pembelajaran (ATP)',
                'deskripsi'      => 'Disusun 1 kali setahun',
                'urutan'         => 5,
                'frekuensi'      => 'tahunan',
                'sections'       => [
                    ['label' => 'Tujuan Pembelajaran', 'field_key' => 'tp', 'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Alur Urutan (Semester 1 & 2)', 'field_key' => 'alur', 'field_type' => 'richtext', 'urutan' => 2],
                    ['label' => 'Alokasi Waktu', 'field_key' => 'alokasi_waktu', 'field_type' => 'text', 'urutan' => 3],
                ],
            ],
            [
                'nama_perangkat' => 'Kriteria Ketercapaian Tujuan Pembelajaran (KKTP)',
                'deskripsi'      => 'Disusun 1 kali setahun',
                'urutan'         => 6,
                'frekuensi'      => 'tahunan',
                'sections'       => [
                    ['label' => 'Tujuan Pembelajaran', 'field_key' => 'tp', 'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Indikator Ketercapaian', 'field_key' => 'indikator', 'field_type' => 'textarea', 'urutan' => 2],
                    ['label' => 'Interval Nilai', 'field_key' => 'interval', 'field_type' => 'text', 'urutan' => 3],
                ],
            ],
            [
                'nama_perangkat' => 'Modul Ajar / RPP',
                'deskripsi'      => 'Disusun per bab',
                'urutan'         => 7,
                'frekuensi'      => 'per_bab',
                'sections'       => [
                    ['label' => 'Informasi Umum', 'field_key' => 'informasi_umum', 'field_type' => 'richtext', 'urutan' => 1],
                    ['label' => 'Kompetensi Inti', 'field_key' => 'kompetensi_inti', 'field_type' => 'richtext', 'urutan' => 2],
                    ['label' => 'Langkah-langkah Pembelajaran', 'field_key' => 'langkah_pembelajaran', 'field_type' => 'richtext', 'urutan' => 3],
                    ['label' => 'Asesmen/Penilaian', 'field_key' => 'asesmen', 'field_type' => 'richtext', 'urutan' => 4],
                    ['label' => 'Lampiran', 'field_key' => 'lampiran', 'field_type' => 'richtext', 'urutan' => 5],
                ],
            ],
            [
                'nama_perangkat' => 'Soal Sumatif',
                'deskripsi'      => 'Disusun 1 kali per semester (2x setahun)',
                'urutan'         => 8,
                'frekuensi'      => 'semesteran',
                'sections'       => [
                    ['label' => 'Kisi-kisi Soal', 'field_key' => 'kisi_kisi', 'field_type' => 'richtext', 'urutan' => 1],
                    ['label' => 'Butir Soal', 'field_key' => 'butir_soal', 'field_type' => 'richtext', 'urutan' => 2],
                    ['label' => 'Kunci Jawaban & Pedoman Penskoran', 'field_key' => 'kunci_jawaban', 'field_type' => 'richtext', 'urutan' => 3],
                ],
            ],
        ];

        foreach ($templates as $t) {
            $sections = $t['sections'];
            unset($t['sections']);

            $template = PerangkatTemplate::create($t);

            foreach ($sections as $s) {
                $template->sections()->create(array_merge($s, ['is_required' => true]));
            }
        }
    }
}