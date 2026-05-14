<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    protected $fillable = [
        'mapel_id',
        'tahun_ajaran',
        'nama_perangkat',
        'judul',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}