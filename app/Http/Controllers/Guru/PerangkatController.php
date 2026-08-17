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
    public function index(\Illuminate\Http\Request $request)
    {
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $activeTahun = \App\Models\TahunAjaran::where('is_active', 1)->first();
        
        $selectedTahunId = $request->query('tahun_ajaran_id', $activeTahun ? $activeTahun->id : null);
        $selectedTahunObj = \App\Models\TahunAjaran::find($selectedTahunId);
        $selectedTahunName = $selectedTahunObj ? $selectedTahunObj->nama : ($activeTahun ? $activeTahun->nama : '2025/2026');

        $mengajarRaw = \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')
            ->where('user_id', Auth::id())
            ->where('tahun_ajaran', $selectedTahunName)
            ->get();

        $mapelIds = collect($mengajarRaw)->pluck('mapel_id')->unique();
        $kelasIds = collect($mengajarRaw)->pluck('kelas_id')->unique();
        $mapels = \App\Models\Mapel::whereIn('id', $mapelIds)->get()->keyBy('id');
        $kelases = \App\Models\Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');

        $combinations = collect();

        $uniqueAssignments = collect($mengajarRaw)->unique(function ($assignment) use ($kelases) {
            $kelas = $kelases->get($assignment->kelas_id);
            $simpleName = $kelas ? trim(preg_replace('/\d+$/', '', $kelas->nama_kelas)) : '';
            return $assignment->mapel_id . '-' . $simpleName;
        });

        $totalTemplates = \App\Models\PerangkatTemplate::where('is_active', true)->count();

        foreach ($uniqueAssignments as $assignment) {
            $mapel = $mapels->get($assignment->mapel_id);
            $kelas = $kelases->get($assignment->kelas_id);
            if ($mapel && $kelas) {
                $simpleName = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
                
                $kelasCopy = clone $kelas;
                $kelasCopy->nama_kelas_simple = $simpleName;
                
                $completed = \App\Models\PerangkatGuru::where([
                    'user_id' => Auth::id(),
                    'mapel_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran' => $selectedTahunName,
                ])
                ->where(function ($q) {
                    $q->where('is_completed', true)
                      ->orWhereIn('status', ['submitted', 'approved']);
                })
                ->count();

                $revisions = \App\Models\PerangkatGuru::where([
                    'user_id' => Auth::id(),
                    'mapel_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran' => $selectedTahunName,
                    'status' => 'revisi',
                ])->count();
                
                $combinations->push((object)[
                    'mapel' => $mapel,
                    'kelas' => $kelasCopy,
                    'completed_count' => $completed,
                    'revision_count' => $revisions,
                ]);
            }
        }

        return view('guru.perangkat.index', compact('combinations', 'tahunAjarans', 'selectedTahunId', 'totalTemplates', 'selectedTahunName'));
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

        // Find the mapel and class matching the selected tahun ajaran
        $targetMapel = \App\Models\Mapel::where('nama_mapel', $mapel->nama_mapel)
            ->where('tahun_ajaran', $selectedTahun)
            ->first();

        // Find all class IDs of the same simplified name in the selected year
        $targetKelasList = \App\Models\Kelas::where('tahun_ajaran', $selectedTahun)
            ->get()
            ->filter(function($k) use ($kelas) {
                return trim(preg_replace('/\d+$/', '', $k->nama_kelas)) === $kelas->nama_kelas_simple;
            });

        // Check if the teacher actually teaches this combination in the selected year
        $isTeaching = false;
        $representativeKelas = null;
        if ($targetMapel && $targetKelasList->isNotEmpty()) {
            $firstAssignment = \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')
                ->where('user_id', Auth::id())
                ->where('mapel_id', $targetMapel->id)
                ->whereIn('kelas_id', $targetKelasList->pluck('id'))
                ->where('tahun_ajaran', $selectedTahun)
                ->first();
                
            if ($firstAssignment) {
                $isTeaching = true;
                $representativeKelas = \App\Models\Kelas::find($firstAssignment->kelas_id);
            }
        }

        $templates = PerangkatTemplate::where('is_active', true)
                        ->with(['tenggatWaktus' => function($q) use ($selectedTahun) {
                            $q->where('tahun_ajaran', $selectedTahun);
                        }])
                        ->orderBy('urutan')
                        ->get();

        $progress = collect();
        if ($isTeaching && $targetMapel && $representativeKelas) {
            $progress = PerangkatGuru::where([
                'user_id'   => Auth::id(),
                'mapel_id'  => $targetMapel->id,
                'kelas_id'  => $representativeKelas->id,
                'tahun_ajaran' => $selectedTahun,
            ])->get()->groupBy('perangkat_template_id');
            
            // Override mapel and kelas for links in view
            $mapel = $targetMapel;
            $kelas = $representativeKelas;
            $kelas->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
        }

        // Get the list of all years where the teacher teaches this subject-class combination
        $teachingYears = [];
        foreach ($tahunAjarans as $ta) {
            $m = \App\Models\Mapel::where('nama_mapel', $mapel->nama_mapel)->where('tahun_ajaran', $ta->nama)->first();
            if ($m) {
                $k_ids = \App\Models\Kelas::where('tahun_ajaran', $ta->nama)
                    ->get()
                    ->filter(function($k) use ($kelas) {
                        return trim(preg_replace('/\d+$/', '', $k->nama_kelas)) === $kelas->nama_kelas_simple;
                    })
                    ->pluck('id');
                
                if ($k_ids->isNotEmpty()) {
                    $hasAssign = \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')
                        ->where('user_id', Auth::id())
                        ->where('mapel_id', $m->id)
                        ->whereIn('kelas_id', $k_ids)
                        ->where('tahun_ajaran', $ta->nama)
                        ->exists();
                    if ($hasAssign) {
                        $teachingYears[] = $ta->nama;
                    }
                }
            }
        }

        return view('guru.perangkat.kelas', compact('mapel', 'kelas', 'templates', 'progress', 'tahunAjarans', 'selectedTahun', 'isTeaching', 'teachingYears'));
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