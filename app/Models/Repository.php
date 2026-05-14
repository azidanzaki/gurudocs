<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $fillable = [
        'user_id',
        'tahun_ajaran',
        'semester',
        'judul',
        'deskripsi',
        'foto_kegiatan',
        'sertifikat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}