<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruDokumenadm extends Controller
{
    // Menampilkan halaman history guru
    public function index()
    {
        return view('guru.dokumenadmguru');
    }
}
