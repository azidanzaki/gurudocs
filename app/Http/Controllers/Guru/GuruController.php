<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PerangkatGuru;
use App\Models\Repository;

class GuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        // Jumlah kegiatan repository
        $totalRepository = Repository::where('user_id', $user->id)
            ->count();


        // Jumlah perangkat selesai
        $totalSelesai = PerangkatGuru::where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();


        // Jumlah perangkat draft
        $totalDraft = PerangkatGuru::where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();


        return view('guru.dashboard.index', compact(
            'totalRepository',
            'totalSelesai',
            'totalDraft'
        ));
    }
}