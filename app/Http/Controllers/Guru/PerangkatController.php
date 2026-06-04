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
        // Only classes this teacher is assigned to for this specific mapel
        $allKelas = Auth::user()->kelasForMapel($mapel->id);

        // Group by grade (e.g. VII, VIII, IX)
        $kelas = $allKelas->map(function ($k) {
            $k->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $k->nama_kelas));
            return $k;
        })->unique('nama_kelas_simple');

        return view('guru.perangkat.show', compact('mapel', 'kelas'));
    }

    // Step 3: Class selected — show available perangkat templates + progress
    public function showKelas(\Illuminate\Http\Request $request, Mapel $mapel, Kelas $kelas)
    {
        $kelas->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));

        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $selectedTahun = $request->query('tahun_ajaran', $tahunAjarans->first()->nama ?? '2025/2026');

        $templates = PerangkatTemplate::where('is_active', true)
                        ->with(['tenggatWaktus' => function($q) use ($selectedTahun) {
                            $q->where('tahun_ajaran', $selectedTahun);
                        }])
                        ->orderBy('urutan')
                        ->get();

        $currentYear = now()->year;

        // Check existing progress for each template
        $progress = PerangkatGuru::where([
            'user_id'   => Auth::id(),
            'mapel_id'  => $mapel->id,
            'kelas_id'  => $kelas->id,
            'tahun_ajaran' => $selectedTahun,
        ])->get()->groupBy('perangkat_template_id');

        return view('guru.perangkat.kelas', compact('mapel', 'kelas', 'templates', 'progress', 'tahunAjarans', 'selectedTahun'));
    }

    public function history(\Illuminate\Http\Request $request)
    {
        $availableYears = PerangkatGuru::where('user_id', Auth::id())
            ->where(function($q) {
                $q->where('tahun', '<', now()->year)->orWhereNull('tahun');
            })
            ->distinct()
            ->pluck('tahun')
            ->sortDesc();

        $selectedYear = $request->get('tahun', $availableYears->first());

        $historyItems = collect();
        if ($selectedYear || $availableYears->contains(null)) {
            $query = PerangkatGuru::where('user_id', Auth::id())
                ->with(['template', 'mapel', 'kelas']);
            
            if ($selectedYear) {
                $query->where('tahun', $selectedYear);
            } else {
                $query->whereNull('tahun');
            }
            $historyItems = $query->orderBy('mapel_id')->orderBy('kelas_id')->get();
        }

        return view('guru.perangkat.history', compact('availableYears', 'selectedYear', 'historyItems'));
    }
}