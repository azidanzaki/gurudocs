<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenAdm extends Model
{
    protected $table = 'dokumen_adm';

    protected $fillable = [
        'judul',
        'jenis_dokumen',
        'tahun',
        'file_word',
        'file_pdf',
        'created_by'
    ];
}