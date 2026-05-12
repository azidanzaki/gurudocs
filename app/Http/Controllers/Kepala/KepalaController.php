<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;

class KepalaController extends Controller
{
    public function dashboard()
    {
        return view('kepala.dashboard');
    }

    public function penilaian()
    {
        return view('kepala.penilaian');
    }

    public function repository()
    {
        return view('kepala.repository');
    }
}