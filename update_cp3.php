<?php
use App\Models\PerangkatTemplateSection;

PerangkatTemplateSection::where('perangkat_template_id', 3)->delete();

$sections = [
    [
        'perangkat_template_id' => 3,
        'label' => 'Rasional Mata Pelajaran',
        'field_key' => 'rasional',
        'field_type' => 'richtext',
        'is_required' => true,
        'urutan' => 1,
    ],
    [
        'perangkat_template_id' => 3,
        'label' => 'Tujuan Mata Pelajaran',
        'field_key' => 'tujuan',
        'field_type' => 'richtext',
        'is_required' => true,
        'urutan' => 2,
    ],
    [
        'perangkat_template_id' => 3,
        'label' => 'Karakteristik Mata Pelajaran',
        'field_key' => 'karakteristik',
        'field_type' => 'richtext',
        'is_required' => true,
        'urutan' => 3,
    ],
    [
        'perangkat_template_id' => 3,
        'label' => 'Elemen Mata Pelajaran',
        'field_key' => 'elemen_mata_pelajaran',
        'field_type' => 'richtext',
        'is_required' => true,
        'urutan' => 4,
    ],
    [
        'perangkat_template_id' => 3,
        'label' => 'Capaian Pembelajaran Setiap Fase',
        'field_key' => 'capaian_per_fase',
        'field_type' => 'richtext',
        'is_required' => true,
        'urutan' => 5,
    ]
];

foreach ($sections as $sec) {
    PerangkatTemplateSection::create($sec);
}
echo "Template CP berhasil diupdate kembali ke 5 kolom richtext.\n";
