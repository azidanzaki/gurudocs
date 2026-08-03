<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgIndikator extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_id', 'nama'];

    public function kategori()
    {
        return $this->belongsTo(PkgKategori::class, 'kategori_id');
    }
}
