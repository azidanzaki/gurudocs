<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruRepository extends Controller
{
    // Menampilkan halaman history guru
    public function index()
    {
        return view('guru.repositoryguru');
    }
}
