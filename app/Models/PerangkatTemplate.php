<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatTemplate extends Model
{
    protected $fillable = ['nama_perangkat', 'deskripsi', 'urutan', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function sections()
    {
        return $this->hasMany(PerangkatTemplateSection::class)
                    ->orderBy('urutan');
    }

    public function perangkatGurus()
    {
        return $this->hasMany(PerangkatGuru::class);
    }
}