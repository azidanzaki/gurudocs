<?php
use App\Models\PerangkatTemplateSection;

PerangkatTemplateSection::where('perangkat_template_id', 3)->delete();

PerangkatTemplateSection::create([
    'perangkat_template_id' => 3,
    'label' => 'Isi Dokumen Capaian Pembelajaran',
    'field_key' => 'isi_dokumen_cp',
    'field_type' => 'richtext',
    'is_required' => true,
    'urutan' => 1,
]);

echo "Template CP dikembalikan ke 1 bagian WYSIWYG besar.\n";
