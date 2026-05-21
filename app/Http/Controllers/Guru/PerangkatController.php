<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\PerangkatGuru;
use App\Models\PerangkatTemplate;
use Illuminate\Support\Facades\Auth;

class PerangkatController extends Controller
{
    // Step 1: List of subjects the teacher handles
    public function index()
    {
        $mapels = Auth::user()->mapels;
        return view('guru.perangkat.index', compact('mapels'));
    }

    // Step 2: "siapkan perangkat" clicked — show classes for this subject
    public function show(Mapel $mapel)
    {
        // Only classes this teacher is assigned to
        $kelas = Auth::user()->kelas;

        return view('guru.perangkat.show', compact('mapel', 'kelas'));
    }

    // Step 3: Class selected — show available perangkat templates + progress
    public function showKelas(Mapel $mapel, Kelas $kelas)
    {
        $templates = PerangkatTemplate::where('is_active', true)
                        ->orderBy('urutan')
                        ->get();

        // Check existing progress for each template
        $progress = PerangkatGuru::where([
            'user_id'   => Auth::id(),
            'mapel_id'  => $mapel->id,
            'kelas_id'  => $kelas->id,
        ])->get()->keyBy('perangkat_template_id');

        return view('guru.perangkat.kelas', compact('mapel', 'kelas', 'templates', 'progress'));
    }
}