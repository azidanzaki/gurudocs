<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruProfil extends Controller
{
    // Menampilkan halaman profil guru
    public function index()
    {
        return view('profilguru');
    }
}
