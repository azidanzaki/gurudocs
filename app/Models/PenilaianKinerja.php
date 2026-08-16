<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianKinerja extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data_penilaian' => 'array',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
