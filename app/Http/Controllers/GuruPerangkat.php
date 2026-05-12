<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruPerangkat extends Controller
{
    // Menampilkan halaman history guru
    public function index()
    {
        return view('guru.perangkatguru');
    }
}
