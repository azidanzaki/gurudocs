<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruDokumen extends Controller
{
    // Menampilkan halaman dokumen guru
    public function index()
    {
        return view('dokumenguru');
    }
}
