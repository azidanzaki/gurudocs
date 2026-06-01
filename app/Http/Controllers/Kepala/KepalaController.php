<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;

class KepalaController extends Controller
{
    public function index()
    {
        $totalGuru = \App\Models\User::where('role', 'guru')->count();
        $totalMapel = \App\Models\Mapel::count();
        
        $totalSelesai = \App\Models\PerangkatGuru::where('is_completed', true)->count();
        $totalDraft = \App\Models\PerangkatGuru::where('status', 'draft')->count();

        $recentSubmissions = \App\Models\PerangkatGuru::where('is_completed', true)
            ->with(['user', 'template', 'mapel', 'kelas'])
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        return view('kepala.dasboard.index', compact(
            'totalGuru',
            'totalMapel',
            'totalSelesai',
            'totalDraft',
            'recentSubmissions'
        ));
    }
}