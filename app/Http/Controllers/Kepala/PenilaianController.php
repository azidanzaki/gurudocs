<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;

class PenilaianController extends Controller
{
    public function index()
    {
        return view('kepala.penilaian.index');
    }
}