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
                'nama_perangkat' => 'RPP',
                'deskripsi'      => 'Rencana Pelaksanaan Pembelajaran',
                'urutan'         => 1,
                'sections'       => [
                    ['label' => 'Kompetensi Dasar',       'field_key' => 'kompetensi_dasar',       'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Tujuan Pembelajaran',    'field_key' => 'tujuan_pembelajaran',    'field_type' => 'textarea', 'urutan' => 2],
                    ['label' => 'Materi Pembelajaran',    'field_key' => 'materi_pembelajaran',    'field_type' => 'richtext', 'urutan' => 3],
                    ['label' => 'Metode Pembelajaran',    'field_key' => 'metode_pembelajaran',    'field_type' => 'textarea', 'urutan' => 4],
                    ['label' => 'Langkah-Langkah Kegiatan', 'field_key' => 'langkah_kegiatan',    'field_type' => 'richtext', 'urutan' => 5],
                    ['label' => 'Alat dan Bahan',         'field_key' => 'alat_bahan',             'field_type' => 'textarea', 'urutan' => 6],
                    ['label' => 'Penilaian',              'field_key' => 'penilaian',              'field_type' => 'textarea', 'urutan' => 7],
                ],
            ],
            [
                'nama_perangkat' => 'Silabus',
                'deskripsi'      => 'Silabus Mata Pelajaran',
                'urutan'         => 2,
                'sections'       => [
                    ['label' => 'Standar Kompetensi',    'field_key' => 'standar_kompetensi',     'field_type' => 'textarea', 'urutan' => 1],
                    ['label' => 'Kompetensi Dasar',      'field_key' => 'kompetensi_dasar',       'field_type' => 'textarea', 'urutan' => 2],
                    ['label' => 'Materi Pokok',          'field_key' => 'materi_pokok',           'field_type' => 'textarea', 'urutan' => 3],
                    ['label' => 'Kegiatan Pembelajaran', 'field_key' => 'kegiatan_pembelajaran',  'field_type' => 'richtext', 'urutan' => 4],
                    ['label' => 'Indikator',             'field_key' => 'indikator',              'field_type' => 'textarea', 'urutan' => 5],
                    ['label' => 'Alokasi Waktu',         'field_key' => 'alokasi_waktu',          'field_type' => 'text',     'urutan' => 6],
                    ['label' => 'Sumber Belajar',        'field_key' => 'sumber_belajar',         'field_type' => 'textarea', 'urutan' => 7],
                ],
            ],
            [
                'nama_perangkat' => 'Prota',
                'deskripsi'      => 'Program Tahunan',
                'urutan'         => 3,
                'sections'       => [
                    ['label' => 'Semester 1 — Kompetensi Dasar & Alokasi Waktu', 'field_key' => 'prota_semester1', 'field_type' => 'richtext', 'urutan' => 1],
                    ['label' => 'Semester 2 — Kompetensi Dasar & Alokasi Waktu', 'field_key' => 'prota_semester2', 'field_type' => 'richtext', 'urutan' => 2],
                    ['label' => 'Jumlah Jam Efektif',                            'field_key' => 'jam_efektif',     'field_type' => 'text',     'urutan' => 3],
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