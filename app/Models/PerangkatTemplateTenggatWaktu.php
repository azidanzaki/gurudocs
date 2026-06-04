<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerangkatTemplateTenggatWaktu extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function template()
    {
        return $this->belongsTo(PerangkatTemplate::class, 'perangkat_template_id');
    }
}
