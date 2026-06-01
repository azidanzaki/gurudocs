<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PerangkatGuru;

class GuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalKelas = $user->kelas()->count();
        $totalMapel = $user->mapels()->count();

        $totalSelesai = PerangkatGuru::where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();

        $totalDraft = PerangkatGuru::where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        $draftTerbaru = PerangkatGuru::where('user_id', $user->id)
            ->where('status', 'draft')
            ->with(['template', 'mapel', 'kelas'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('guru.dashboard.index', compact(
            'totalKelas',
            'totalMapel',
            'totalSelesai',
            'totalDraft',
            'draftTerbaru'
        ));
    }
}