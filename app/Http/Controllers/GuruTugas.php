<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruTugas extends Controller
{
    // Menampilkan tugas dashboard guru
    public function index()
    {
        return view('guru.tugasguru');
    }
}
