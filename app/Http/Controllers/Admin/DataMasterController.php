<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    public function index(Request $request)
    {
        // For Mata Pelajaran
        $mapelQuery = Mapel::query();
        if ($request->filled('search_mapel')) {
            $mapelQuery->where('nama_mapel', 'like', "%{$request->search_mapel}%");
        }
        $mapels = $mapelQuery->latest()->paginate(10, ['*'], 'mapel_page')->withQueryString();

        // For Kelas (Get all to group by VII, VIII, IX in view)
        $kelasQuery = Kelas::query();
        if ($request->filled('search_kelas')) {
            $kelasQuery->where('nama_kelas', 'like', "%{$request->search_kelas}%");
        }
        $kelas = $kelasQuery->orderBy('nama_kelas')->get();

        // For Guru
        $guruQuery = User::where('role', 'guru');
        if ($request->filled('search_guru')) {
            $guruQuery->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search_guru}%")
                  ->orWhere('nip', 'like', "%{$request->search_guru}%");
            });
        }
        $gurus = $guruQuery->latest()->paginate(10, ['*'], 'guru_page')->withQueryString();
        
        // Eager load relations for gurus to show assigned mapel and kelas
        $gurus->load(['mapels', 'kelas']);
        
        // Get all mapel and kelas for the assign dropdowns
        $allMapels = Mapel::orderBy('nama_mapel')->get();
        $allKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.data_master.index', compact('mapels', 'kelas', 'gurus', 'allMapels', 'allKelas'));
    }

    // --- MATA PELAJARAN ---

    public function storeMapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|max:255',
        ]);

        Mapel::create([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.data_master.index')->with('success', 'Mata Pelajaran berhasil ditambahkan')->with('tab', 'mapel');
    }

    public function updateMapel(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|max:255',
        ]);

        $mapel = Mapel::findOrFail($id);
        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.data_master.index')->with('success', 'Mata Pelajaran berhasil diupdate')->with('tab', 'mapel');
    }

    public function destroyMapel($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.data_master.index')->with('success', 'Mata Pelajaran berhasil dihapus')->with('tab', 'mapel');
    }

    // --- KELAS ---

    public function storeKelas(Request $request)
    {
        if ($request->has('prefix')) {
            $prefix = $request->prefix;
            $request->validate([
                'prefix' => 'required|in:VII,VIII,IX',
            ]);

            $classes = Kelas::where('nama_kelas', 'like', $prefix . ' %')->get();
            $maxNum = 0;
            foreach ($classes as $c) {
                $parts = explode(' ', $c->nama_kelas);
                if (count($parts) >= 2) {
                    $num = (int) end($parts);
                    if ($num > $maxNum) {
                        $maxNum = $num;
                    }
                }
            }
            $nextNum = $maxNum + 1;
            $nama_kelas = $prefix . ' ' . $nextNum;

            Kelas::create(['nama_kelas' => $nama_kelas]);

            return redirect()->route('admin.data_master.index')->with('success', "Kelas $nama_kelas berhasil ditambahkan")->with('tab', 'kelas');
        }

        $request->validate([
            'nama_kelas' => 'required|max:255',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('admin.data_master.index')->with('success', 'Kelas berhasil ditambahkan')->with('tab', 'kelas');
    }

    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|max:255',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('admin.data_master.index')->with('success', 'Kelas berhasil diupdate')->with('tab', 'kelas');
    }

    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.data_master.index')->with('success', 'Kelas berhasil dihapus')->with('tab', 'kelas');
    }

    // --- PENUGASAN GURU ---

    public function storePenugasan(Request $request, $userId)
    {
        $request->validate([
            'penugasans' => 'required|array',
            'penugasans.*.mapel_id' => 'required|exists:mapels,id',
            'penugasans.*.kelas_ids' => 'required|array',
            'penugasans.*.kelas_ids.*' => 'exists:kelas,id',
        ]);

        $user = User::findOrFail($userId);
        
        if ($user->role !== 'guru') {
            return redirect()->route('admin.data_master.index')->with('error', 'User bukan seorang guru')->with('tab', 'guru');
        }

        $addedCount = 0;
        foreach ($request->penugasans as $penugasan) {
            $mapelId = $penugasan['mapel_id'];
            foreach ($penugasan['kelas_ids'] as $kelasId) {
                // Check if assignment already exists
                $exists = DB::table('guru_mapel_kelas')
                    ->where('user_id', $user->id)
                    ->where('mapel_id', $mapelId)
                    ->where('kelas_id', $kelasId)
                    ->exists();

                if (!$exists) {
                    DB::table('guru_mapel_kelas')->insert([
                        'user_id' => $user->id,
                        'mapel_id' => $mapelId,
                        'kelas_id' => $kelasId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $addedCount++;
                }
            }
        }

        return redirect()->route('admin.data_master.index')->with('success', "$addedCount penugasan guru berhasil ditambahkan")->with('tab', 'guru');
    }

    public function destroyPenugasan($id)
    {
        $assignment = DB::table('guru_mapel_kelas')->where('id', $id)->first();
        
        if (!$assignment) {
            return redirect()->route('admin.data_master.index')->with('error', 'Penugasan tidak ditemukan')->with('tab', 'guru');
        }

        DB::table('guru_mapel_kelas')->where('id', $id)->delete();

        return redirect()->route('admin.data_master.index')->with('success', 'Penugasan guru berhasil dihapus')->with('tab', 'guru');
    }
}
