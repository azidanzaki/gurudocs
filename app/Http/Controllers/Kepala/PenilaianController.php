<?php


namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PerangkatGuru;
use App\Models\PenilaianKinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    public function index()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('kepala.penilaian.index', compact('gurus'));
    }

    public function showGuru($id)
    {
        $guru = User::findOrFail($id);
        if ($guru->role !== 'guru') {
            abort(404);
        }

        $mengajarRaw = $guru->mengajar();
        $mapelIds = collect($mengajarRaw)->pluck('mapel_id')->unique();
        $kelasIds = collect($mengajarRaw)->pluck('kelas_id')->unique();
        $mapels = \App\Models\Mapel::whereIn('id', $mapelIds)->get()->keyBy('id');
        $kelases = \App\Models\Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');

        $mengajarAssignments = collect($mengajarRaw)->map(function ($assignment) use ($kelases) {
            $kelas = $kelases->get($assignment->kelas_id);
            if ($kelas) {
                $assignment->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
                $kelas->nama_kelas = $assignment->nama_kelas_simple; // Override for view
            }
            return $assignment;
        })->unique(function ($assignment) {
            return $assignment->mapel_id . '-' . ($assignment->nama_kelas_simple ?? $assignment->kelas_id);
        })->values();

        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $activeTahun = \App\Models\TahunAjaran::where('is_active', 1)->orderBy('nama', 'desc')->first() ?? $tahunAjarans->first();
        
        $selectedTahunId = request()->query('tahun_ajaran_id', $activeTahun ? $activeTahun->id : null);

        $completedCounts = \App\Models\PenilaianKinerja::where('guru_id', $id)
            ->where('tahun_ajaran_id', $selectedTahunId)
            ->where('status', 'submitted')
            ->get()
            ->groupBy(function($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            })
            ->map->count();

        return view('kepala.penilaian.show', compact('guru', 'mengajarAssignments', 'mapels', 'kelases', 'completedCounts', 'tahunAjarans', 'selectedTahunId', 'activeTahun'));
    }

    public function kelengkapanDokumen(Request $request, $id)
    {
        $guru = User::findOrFail($id);
        if ($guru->role !== 'guru') {
            abort(404);
        }

        // Existing documents
        $existingDokumens = \App\Models\PerangkatGuru::with(['mapel', 'kelas', 'template'])
            ->where('user_id', $id)
            ->get()
            ->keyBy(function ($item) {
                return $item->perangkat_template_id . '-' . $item->mapel_id . '-' . $item->kelas_id;
            });

        // Templates and teaching assignments
        $templates = \App\Models\PerangkatTemplate::where('is_active', true)->orderBy('urutan')->get();
        $mengajarRaw = $guru->mengajar(); 
        
        $mapelIds = collect($mengajarRaw)->pluck('mapel_id')->unique();
        $kelasIds = collect($mengajarRaw)->pluck('kelas_id')->unique();
        $semuaMapels = \App\Models\Mapel::whereIn('id', $mapelIds)->get()->keyBy('id');
        $kelases = \App\Models\Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');
        
        $mengajarAssignments = collect($mengajarRaw)->map(function ($assignment) use ($kelases) {
            $kelas = $kelases->get($assignment->kelas_id);
            if ($kelas) {
                $assignment->nama_kelas_simple = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
                $kelas->nama_kelas = $assignment->nama_kelas_simple; // Override for view
            }
            return $assignment;
        })->unique(function ($assignment) {
            return $assignment->mapel_id . '-' . ($assignment->nama_kelas_simple ?? $assignment->kelas_id);
        })->values();

        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $activeTahun = \App\Models\TahunAjaran::where('is_active', 1)->orderBy('nama', 'desc')->first() ?? $tahunAjarans->first();

        $selectedMapelKelas = $request->query('mapel_kelas');
        $selectedTahun = $request->query('tahun_ajaran', $activeTahun ? $activeTahun->nama : null);

        $dokumens = collect();

        foreach ($mengajarAssignments as $assignment) {
            $mapelId = $assignment->mapel_id;
            $kelasId = $assignment->kelas_id;
            $mapelKelasStr = $mapelId . '-' . $kelasId;
            
            // Terapkan filter mapel_kelas jika dipilih
            if ($selectedMapelKelas && $mapelKelasStr != $selectedMapelKelas) continue;
            
            $mapel = $semuaMapels->get($mapelId);
            $kelas = $kelases->get($kelasId);

            if (!$mapel || !$kelas) continue;

            foreach ($templates as $template) {
                $key = $template->id . '-' . $mapelId . '-' . $kelasId;
                
                if ($existingDokumens->has($key)) {
                    $dokumen = $existingDokumens->get($key);
                    // Terapkan filter tahun ajaran pada dokumen yang sudah ada
                    if ($selectedTahun && $dokumen->tahun_ajaran != $selectedTahun) continue;
                    
                    $dokumens->push($dokumen);
                } else {
                    // Jika difilter berdasar tahun ajaran tertentu, yang 'belum dibuat' mungkin tak relevan
                    // jika dianggap tahun ajaran mengikat pada dokumennya. Namun untuk list, kita munculkan saja.
                    if ($selectedTahun && $selectedTahun != ($activeTahun ? $activeTahun->nama : null)) continue;
                    
                    // Create a dummy object representing "Belum Dibuat"
                    $dummy = new \stdClass();
                    $dummy->template = (object)['nama_perangkat' => $template->nama_perangkat];
                    $dummy->mapel = (object)['nama_mapel' => $mapel->nama_mapel];
                    $dummy->kelas = (object)['nama_kelas' => $kelas->nama_kelas];
                    $dummy->status = 'belum_dibuat';
                    $dummy->id = null; // No ID yet
                    $dokumens->push($dummy);
                }
            }
        }

        $kegiatansQuery = \App\Models\Repository::where('user_id', $id)->orderBy('created_at', 'desc');
        if ($selectedTahun) {
            $kegiatansQuery->where('tahun_ajaran', $selectedTahun);
        }
        $kegiatans = $kegiatansQuery->get();

        return view('kepala.penilaian.kelengkapan', compact('guru', 'dokumens', 'kegiatans', 'mengajarAssignments', 'semuaMapels', 'kelases', 'tahunAjarans', 'selectedMapelKelas', 'selectedTahun'));
    }

    public function pkg(Request $request, $guruId)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $kelasId = $request->query('kelas_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $kelas = $kelasId ? \App\Models\Kelas::find($kelasId) : null;
        if ($kelas) {
            $kelas->nama_kelas = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
        }
        
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $activeTahun = \App\Models\TahunAjaran::where('is_active', 1)->orderBy('nama', 'desc')->first() ?? $tahunAjarans->first();
        $selectedTahunId = $request->query('tahun_ajaran_id', $activeTahun ? $activeTahun->id : null);
        $selectedTahunObj = \App\Models\TahunAjaran::find($selectedTahunId);

        $penilaians = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $selectedTahunId)
            ->get()
            ->keyBy('aspek');

        return view('kepala.penilaian.pkg', compact('guru', 'mapel', 'kelas', 'penilaians', 'tahunAjarans', 'selectedTahunId', 'selectedTahunObj'));
    }

    public function start(Request $request, $guruId, $aspect)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $kelasId = $request->query('kelas_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $kelas = $kelasId ? \App\Models\Kelas::find($kelasId) : null;
        if ($kelas) {
            $kelas->nama_kelas = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
        }
        
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
        $activeTahun = \App\Models\TahunAjaran::where('is_active', 1)->orderBy('nama', 'desc')->first() ?? $tahunAjarans->first();
        $selectedTahunId = $request->query('tahun_ajaran_id', $activeTahun ? $activeTahun->id : null);
        $selectedTahunObj = \App\Models\TahunAjaran::find($selectedTahunId);

        // Check for existing penilaian
        $penilaian = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $selectedTahunId)
            ->where('aspek', $aspect)
            ->first();

        // Aspek structure
        $indicators = $this->getIndicators($aspect, $selectedTahunId);

        return view('kepala.penilaian.form', compact('guru', 'mapel', 'kelas', 'tahunAjarans', 'selectedTahunId', 'selectedTahunObj', 'aspect', 'penilaian', 'indicators'));
    }

    public function store(Request $request, $guruId, $aspect)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->input('mapel_id');
        $kelasId = $request->input('kelas_id');
        $tahunAjaranId = $request->input('tahun_ajaran_id');
        
        $dataPenilaian = $request->input('penilaian', []);
        
        $totalSkor = 0;
        $count = 0;
        foreach ($dataPenilaian as $category => $items) {
            foreach ($items as $item => $score) {
                if ($score) {
                    $totalSkor += (int) $score;
                    $count++;
                }
            }
        }
        
        $rataRata = $count > 0 ? $totalSkor / $count : 0;
        
        $status = $request->input('action') === 'submit' ? 'submitted' : 'draft';
        $catatan = $request->input('catatan');

        PenilaianKinerja::updateOrCreate(
            [
                'guru_id' => $guruId,
                'mapel_id' => $mapelId,
                'kelas_id' => $kelasId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'aspek' => $aspect,
            ],
            [
                'penilai_id' => auth()->id(),
                'data_penilaian' => $dataPenilaian,
                'total_skor' => $totalSkor,
                'rata_rata' => $rataRata,
                'status' => $status,
                'catatan' => $catatan,
            ]
        );

        if ($status === 'submitted') {
            return redirect()->route('kepala.penilaian.pkg', ['id' => $guruId, 'mapel_id' => $mapelId, 'kelas_id' => $kelasId, 'tahun_ajaran_id' => $tahunAjaranId])
                ->with('success', 'Penilaian Kinerja Guru berhasil disimpan secara final.');
        }

        return redirect()->back()->with('success', 'Draft Penilaian berhasil disimpan.');
    }

    public function cetakPkg(Request $request, $guruId)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $kelasId = $request->query('kelas_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $kelas = $kelasId ? \App\Models\Kelas::find($kelasId) : null;
        if ($kelas) {
            $kelas->nama_kelas = trim(preg_replace('/\d+$/', '', $kelas->nama_kelas));
        }
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $tahunAjaran = $tahunAjaranId ? \App\Models\TahunAjaran::find($tahunAjaranId) : (\App\Models\TahunAjaran::where('is_active', 1)->orderBy('nama', 'desc')->first() ?? \App\Models\TahunAjaran::orderBy('nama', 'desc')->first());

        $penilaians = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaran ? $tahunAjaran->id : null)
            ->get()
            ->keyBy('aspek');



        $requiredAspects = range(1, 7);
        foreach ($requiredAspects as $aspect) {
            if (!isset($penilaians[$aspect]) || $penilaians[$aspect]->status !== 'submitted') {
                \Log::error("Cetak PKG Gagal: Aspek $aspect belum disubmit untuk Guru ID $guruId");
                return redirect()->back()
                    ->with('warning', 'Semua aspek belum selesai. Tidak dapat mencetak PKG.');
            }
        }

        $aspectsData = [];
        foreach ($requiredAspects as $aspect) {
            $aspectsData[$aspect] = [
                'penilaian' => $penilaians[$aspect],
                'indicators' => $this->getIndicators($aspect, $tahunAjaran ? $tahunAjaran->id : null)
            ];
        }

        $data = [
            'guru_name' => $guru->name,
            'guru_nip' => $guru->nip ?? '-',
            'mapel' => $mapel ? $mapel->nama_mapel : 'Semua Mapel',
            'tahun_ajaran' => $tahunAjaran ? $tahunAjaran->nama : '-',
            'penilai' => auth()->user()->name,
            'aspectsData' => $aspectsData
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kepala.penilaian.pdf_pkg', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('PKG_' . str_replace(' ', '_', $guru->name) . '_' . date('Ymd') . '.pdf');
    }

    private function getIndicators($aspect, $tahunAjaranId = null)
    {
        $query = \App\Models\PkgKategori::with('indikators')->where('aspek', $aspect);
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }
        $kategoris = $query->get();
        $indicators = [];
        foreach ($kategoris as $kategori) {
            $indicators[$kategori->nama] = $kategori->indikators->pluck('nama')->toArray();
        }
        return $indicators;
    }

    public function updateDokumenStatus(Request $request, $id)
    {
        $dokumen = PerangkatGuru::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:terima,revisi',
            'catatan_revisi' => 'nullable|string',
        ]);

        if ($request->action === 'terima') {
            $dokumen->status = 'approved';
            $dokumen->catatan_revisi = null;
        } elseif ($request->action === 'revisi') {
            $dokumen->status = 'revisi';
            $dokumen->is_completed = false;
            $dokumen->catatan_revisi = $request->catatan_revisi;
        }

        $dokumen->save();

        return redirect()->back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}