<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatGuruSection extends Model
{
    protected $fillable = [
        'perangkat_guru_id', 'perangkat_template_section_id', 'field_key', 'value',
    ];

    public function perangkatGuru()
    {
        return $this->belongsTo(PerangkatGuru::class);
    }

    public function templateSection()
    {
        return $this->belongsTo(PerangkatTemplateSection::class, 'perangkat_template_section_id');
    }
}