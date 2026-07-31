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
    public function index()
    {
        $mapels = Auth::user()->mapels;
        return view('guru.perangkat.index', compact('mapels'));
    }

    public function show(Mapel $mapel)
    {
        $allKelas = Auth::user()->kelasForMapel($mapel->id);

        $kelas = $allKelas->map(function ($k) {
            $k->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $k->nama_kelas));
            return $k;
        })->unique('nama_kelas_simple');

        return view('guru.perangkat.show', compact('mapel', 'kelas'));
    }

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
        $availableTahunAjarans = PerangkatGuru::where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->whereNotNull('tahun_ajaran')
            ->distinct()
            ->pluck('tahun_ajaran')
            ->sortDesc();

        $selectedTahun = $request->get('tahun_ajaran', 'semua');

        $query = PerangkatGuru::where('user_id', Auth::id())
            ->where('status', 'submitted')
            ->with(['template', 'mapel', 'kelas']);
            
        if ($selectedTahun && $selectedTahun !== 'semua') {
            $query->where('tahun_ajaran', $selectedTahun);
        }
        
        $historyItems = $query->orderBy('submitted_at', 'desc')->get();

        return view('guru.perangkat.history', compact('availableTahunAjarans', 'selectedTahun', 'historyItems'));
    }
}