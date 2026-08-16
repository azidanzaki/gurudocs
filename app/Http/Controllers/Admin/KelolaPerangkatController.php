<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PerangkatTemplate;
use App\Models\PerangkatGuru;
use App\Models\PerangkatTemplateTenggatWaktu;
use App\Models\TahunAjaran;
use App\Models\User;

class KelolaPerangkatController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->get();
        $selectedTahun = $request->query('tahun_ajaran', $tahunAjarans->first()->nama ?? '2025/2026');

        $templates = PerangkatTemplate::with(['tenggatWaktus' => function($q) use ($selectedTahun) {
            $q->where('tahun_ajaran', $selectedTahun);
        }])->get();
        
        $gurus = User::where('role', 'guru')->orderBy('name')->get();
        $perangkatGurus = PerangkatGuru::where('tahun_ajaran', $selectedTahun)
            ->get()
            ->groupBy('perangkat_template_id');

        return view('admin.kelolaperangkat.index', compact('templates', 'tahunAjarans', 'selectedTahun', 'gurus', 'perangkatGurus'));
    }

    public function updateTenggat(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:perangkat_templates,id',
            'tahun_ajaran' => 'required|string',
            'tenggat_waktu' => 'required|date',
        ]);

        PerangkatTemplateTenggatWaktu::updateOrCreate(
            [
                'perangkat_template_id' => $request->template_id,
                'tahun_ajaran' => $request->tahun_ajaran,
            ],
            [
                'tenggat_waktu' => $request->tenggat_waktu,
            ]
        );

        return back()->with('success', 'Tenggat waktu berhasil diperbarui.');
    }

    public function storeTahunAjaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:tahun_ajarans,nama',
        ]);

        $oldActive = TahunAjaran::orderBy('created_at', 'desc')->first();
        $oldYear = $oldActive ? $oldActive->nama : null;

        TahunAjaran::create([
            'nama' => $request->nama,
            'is_active' => true,
        ]);

        if ($oldYear) {
            // Duplicate Mapels
            $oldMapels = \Illuminate\Support\Facades\DB::table('mapels')->where('tahun_ajaran', $oldYear)->get();
            $mapelIdMapping = [];
            foreach ($oldMapels as $m) {
                $newId = \Illuminate\Support\Facades\DB::table('mapels')->insertGetId([
                    'nama_mapel' => $m->nama_mapel,
                    'tahun_ajaran' => $request->nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $mapelIdMapping[$m->id] = $newId;
            }

            // Duplicate Kelas
            $oldKelas = \Illuminate\Support\Facades\DB::table('kelas')->where('tahun_ajaran', $oldYear)->get();
            $kelasIdMapping = [];
            foreach ($oldKelas as $k) {
                $newId = \Illuminate\Support\Facades\DB::table('kelas')->insertGetId([
                    'nama_kelas' => $k->nama_kelas,
                    'tahun_ajaran' => $request->nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $kelasIdMapping[$k->id] = $newId;
            }

            // Duplicate Guru Mapel Kelas assignments
            $oldAssignments = \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')
                ->where('tahun_ajaran', $oldYear)
                ->get();
            
            foreach ($oldAssignments as $assignment) {
                if (isset($mapelIdMapping[$assignment->mapel_id]) && isset($kelasIdMapping[$assignment->kelas_id])) {
                    \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->insert([
                        'user_id' => $assignment->user_id,
                        'mapel_id' => $mapelIdMapping[$assignment->mapel_id],
                        'kelas_id' => $kelasIdMapping[$assignment->kelas_id],
                        'tahun_ajaran' => $request->nama,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $redirectTo = $request->input('redirect_to', 'admin.kelolaperangkat');
        return redirect()->route($redirectTo, ['tahun_ajaran' => $request->nama])->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function reopen($id)
    {
        $perangkat = PerangkatGuru::findOrFail($id);
        
        $perangkat->update([
            'status' => 'draft',
            'is_completed' => false,
            'submitted_at' => null,
        ]);

        return back()->with('success', 'Perangkat berhasil dibuka kembali agar guru dapat melakukan revisi.');
    }
}
