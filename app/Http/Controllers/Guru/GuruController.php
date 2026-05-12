<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class GuruController extends Controller
{
    public function dashboard()
    {
        return view('guru.dashboardguru');
    }

    public function repository()
    {
        return view('guru.repositoryguru');
    }

    public function dokumen()
    {
        return view('guru.dokumenadmguru');
    }

    public function perangkat()
    {
        return view('guru.perangkatguru');
    }

    public function dokumenAdmin()
    {
        return view('guru.dokumenadmguru');
    }
    public function profil()
    {
        return view('guru.profilguru');
    }
}