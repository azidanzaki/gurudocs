<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgKategori extends Model
{
    use HasFactory;

    protected $fillable = ['aspek', 'nama', 'tahun_ajaran_id'];

    public function indikators()
    {
        return $this->hasMany(PkgIndikator::class, 'kategori_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
