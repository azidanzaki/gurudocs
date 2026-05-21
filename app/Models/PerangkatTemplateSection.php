<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatTemplateSection extends Model
{
    protected $fillable = [
        'perangkat_template_id', 'label', 'field_key',
        'field_type', 'placeholder', 'is_required', 'urutan',
    ];

    protected $casts = ['is_required' => 'boolean'];

    public function template()
    {
        return $this->belongsTo(PerangkatTemplate::class, 'perangkat_template_id');
    }
}