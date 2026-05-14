<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
    ];
    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_kelas');
    }

    public function waliKelas()
    {
        return $this->belongsToMany(User::class, 'wali_kelas');
    }
}
