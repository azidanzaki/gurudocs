<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilGuru extends Model
{
    protected $fillable = [
        'user_id',
        'mapel_id',
        'kelas_id',
        'wali_kelas_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Kelas::class, 'wali_kelas_id');
    }
}