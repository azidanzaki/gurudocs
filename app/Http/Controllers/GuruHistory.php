<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruHistory extends Controller
{
    // Menampilkan halaman history guru
    public function index()
    {
        return view('guru.historyguru');
    }
}
