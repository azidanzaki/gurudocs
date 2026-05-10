<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    // Menampilkan halaman dashboard guru
    public function index()
    {
        return view('guru.dashboardguru');
    }
}
