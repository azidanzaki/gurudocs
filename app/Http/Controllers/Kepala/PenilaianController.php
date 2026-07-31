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

        $mengajarAssignments = $guru->mengajar();
        $mapelIds = collect($mengajarAssignments)->pluck('mapel_id')->unique();
        $mapels = \App\Models\Mapel::whereIn('id', $mapelIds)->get();

        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', 1)->first();
        $completedCounts = \App\Models\PenilaianKinerja::where('guru_id', $id)
            ->where('tahun_ajaran_id', $tahunAjaran ? $tahunAjaran->id : null)
            ->where('status', 'submitted')
            ->get()
            ->groupBy('mapel_id')
            ->map->count();

        return view('kepala.penilaian.show', compact('guru', 'mapels', 'completedCounts'));
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
        $mengajarAssignments = $guru->mengajar(); 
        
        $mapelIds = collect($mengajarAssignments)->pluck('mapel_id')->unique();
        $kelasIds = collect($mengajarAssignments)->pluck('kelas_id')->unique();
        $semuaMapels = \App\Models\Mapel::whereIn('id', $mapelIds)->get()->keyBy('id');
        $kelases = \App\Models\Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();

        $selectedMapelId = $request->query('mapel_id');
        $selectedTahun = $request->query('tahun_ajaran');

        $dokumens = collect();

        foreach ($mengajarAssignments as $assignment) {
            $mapelId = $assignment->mapel_id;
            $kelasId = $assignment->kelas_id;
            
            // Terapkan filter mapel jika dipilih
            if ($selectedMapelId && $mapelId != $selectedMapelId) continue;
            
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
                    if ($selectedTahun) continue; // Atau bisa dihilangkan baris ini jika tetap ingin muncul
                    
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

        return view('kepala.penilaian.kelengkapan', compact('guru', 'dokumens', 'kegiatans', 'semuaMapels', 'tahunAjarans', 'selectedMapelId', 'selectedTahun'));
    }

    public function pkg(Request $request, $guruId)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', 1)->first();

        $penilaians = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('tahun_ajaran_id', $tahunAjaran ? $tahunAjaran->id : null)
            ->get()
            ->keyBy('aspek');

        return view('kepala.penilaian.pkg', compact('guru', 'mapel', 'tahunAjaran', 'penilaians'));
    }

    public function start(Request $request, $guruId, $aspect)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', 1)->first();

        // Check for existing penilaian
        $penilaian = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->where('tahun_ajaran_id', $tahunAjaran ? $tahunAjaran->id : null)
            ->where('aspek', $aspect)
            ->first();

        // Aspek 1 structure
        $indicators = $this->getIndicators($aspect);

        return view('kepala.penilaian.form', compact('guru', 'mapel', 'tahunAjaran', 'aspect', 'penilaian', 'indicators'));
    }

    public function store(Request $request, $guruId, $aspect)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->input('mapel_id');
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
            return redirect()->route('kepala.penilaian.pkg', ['id' => $guruId, 'mapel_id' => $mapelId])
                ->with('success', 'Penilaian Kinerja Guru berhasil disimpan secara final.');
        }

        return redirect()->back()->with('success', 'Draft Penilaian berhasil disimpan.');
    }

    public function cetakPkg(Request $request, $guruId)
    {
        $guru = User::findOrFail($guruId);
        $mapelId = $request->query('mapel_id');
        $mapel = $mapelId ? \App\Models\Mapel::find($mapelId) : null;
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', 1)->first();

        $penilaians = PenilaianKinerja::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
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
                'indicators' => $this->getIndicators($aspect)
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

    private function getIndicators($aspect)
    {
        switch ((int) $aspect) {
            case 1:
                return [
                    'Tujuan Pembelajaran' => [
                        'Standar Kompetensi',
                        'Indikator',
                        'Ranah Tujuan (komprehenship)',
                        'Sesuai dengan Kurikulum'
                    ],
                    'Bahan Belajar/Materi Pelajaran' => [
                        'Bahan belajar mengacu/sesuai dengan tujuan',
                        'Bahan belajar disusun secara sistematis',
                        'Menggunakan bahan belajar sesuai dengan kurikulum',
                        'Memberi Pengayaan'
                    ],
                    'Strategi/Metode Pembelajaran' => [
                        'Pemilihan metode disesuaikan dengan tujuan',
                        'Pemilihan metode disesuaikan dengan materi',
                        'Penentuan langkah-langkah proses pembelajaran berdasarkan metode yang digunakan',
                        'Penataan alokasi waktu proses pembelajaran sesuai dengan proporsi.',
                        'Penetapan metode berdasarkan pertimbangan kemampuan siswa.',
                        'Memberi pengayaan'
                    ],
                    'Media Pembelajaran' => [
                        'Media disesuaikan dengan tujuan pembelajaran',
                        'Media disesuaikan dengan materi pembelajaran',
                        'Media disesuaikan dengan kondisi kelas',
                        'Media disesuaikan dengan jenis evaluasi',
                        'Media disesuaikan dengan kemampuan guru',
                        'Media disesuaikan dengan kebutuhan dan perkembangan siswa'
                    ],
                    'Evaluasi' => [
                        'Evaluasi mengacu pada tujuan',
                        'Mencantumkan teknik evaluasi',
                        'Mencantumkan bentuk evaluasi',
                        'Disesuaikan dengan alokasi waktu yang tersedia',
                        'Evaluasi disesuaikan dengan kaidah evaluasi'
                    ]
                ];
            case 2:
                return [
                    'Kemampuan Membuka Pelajaran' => [
                        'Memperhatikan sikap dan tempat duduk siswa',
                        'Memberikan motivasi awal',
                        'Memberikan apersepsi (kaitan materi sebelumnya dengan materi yang akan disampaikan)',
                        'Menyampaikan indikator/tujuan pembelajaran yang akan diberikan',
                        'Memberikan acuan bahan belajar yang akan diberikan'
                    ],
                    'Sikap Guru dalam Proses Pembelajaran' => [
                        'Kejelasan artikulasi suara',
                        'Variasi Gerakan badan tidak mengganggu perhatian siswa',
                        'Antusiasme dalam penampilan',
                        'Mobilitas posisi mengajar'
                    ],
                    'Penguasaan Bahan Belajar (Materi Pelajaran)' => [
                        'Bahan belajar disajikan sesuai dengan langkah-langkah yang direncanakan dalam RPP',
                        'Kejelasan dalam menjelaskan bahan belajar (materi)',
                        'Kejelasan dalam memberikan contoh',
                        'Memiliki wawasan yang luas dalam menyampaikan bahan belajar'
                    ],
                    'Kegiatan Belajar Mengajar (Proses Pembelajaran)' => [
                        'Kesesuaian metode dengan bahan belajar yang disampaikan',
                        'Penyajian bahan belajaran sesuai dengan tujuan/indikator yang telah ditetapkan',
                        'Memiliki keterampilan dalam menanggapi dan merespon pertanyaan siswa.',
                        'Ketepatan dalam penggunaan alokasi waktu yang disediakan'
                    ],
                    'Kemampuan Menggunakan Media Pembelajaran' => [
                        'Memperhatikan prinsip-prinsip penggunaan media',
                        'Ketepatan/kesesuaian penggunaan media dengan materi yang disampaikan',
                        'Memiliki keterampilan dalam penggunaan media pembelajaran',
                        'Membantu meningkatkan perhatian siswa dalam kegiatan pembelajaran'
                    ],
                    'Evaluasi Pembelajaran' => [
                        'Penilaian relevan dengan tujuan yang telah ditetapkan',
                        'Menggunakan bentuk dan jenis ragam penilaian',
                        'Penilaian yang diberikan sesuai dengan RPP',
                        'Menganalisis ketuntasan pembelajaran'
                    ],
                    'Kemampuan Menutup Kegiatan Pembelajaran' => [
                        'Meninjau kembali materi yang telah diberikan [kesimpulan materi]',
                        'Memberi kesempatan untuk bertanya dan menjawab pertanyaan.',
                        'Memberikan kesimpulan kegiatan pembelajaran [refleksi]'
                    ],
                    'Tindak Lanjut/Follow up' => [
                        'Memberikan tugas kepada siswa baik secara individu maupun kelompok',
                        'Menginformasikan materi/bahan belajar yang akan dipelajari berikunya.',
                        'Memberikan motivasi untuk selalu terus belajar'
                    ]
                ];
            case 3:
                return [
                    'Kegiatan Membuka Pembelajaran' => [
                        'Memperhatikan sikap dan tempat duduk siswa',
                        'Memulai pembelajaran setelah siswa siap untuk belajar',
                        'Menjelaskan pentingnya materi pelajaran yang akan dipelajari',
                        'Melakukan Appersepsi (mengkaitkan materi yang disajikan dengan materi yang telah dipelajari sehingga terjadi kesinambungan)',
                        'Kejelasan hubungan antara pendahuluan dengan inti pelajaran dilakukan semenarik mungkin'
                    ],
                    'Kegiatan Menutup Pembelajaran' => [
                        'Kemampuan menyimpulkan KBM dengan tepat',
                        'Kemampuan menggunakan kata-kata yang memebesarkan hati siswa',
                        'Kemampuan memberikan evaluasi lisan maupun tulisan',
                        'Kemampuan memberikan tugas yang sifatnya memberikan pengayaan, dan pendalaman'
                    ]
                ];
            case 4:
                return [
                    'Kegiatan Variasi Pembelajaran' => [
                        'Gerak bebas guru',
                        'Isyarat guru (tangan, badan, wajah)',
                        'Suara guru (variasi kecepatan/besar kecil/intonasi)',
                        'Pemusatan perhatian pada murid (penekanan pada hal yang penting-penting dengan verbal/gestural)',
                        'Pola interaksi (guru-kelompok/guru-murid/murid-murid)',
                        'Pause/diam sejenak (untuk memberi kesempatan pada murid untuk berpikir, memberi penekanan, memberi perhatian)',
                        'Memanfaatkan indra dalam menggunakan media pembelajaran'
                    ]
                ];
            case 5:
                return [
                    'Keterampilan Bertanya' => [
                        'Kejelasan pertanyaan yang disampaikan guru.',
                        'Kejelasan hubungan antara pertanyaan guru dengan masalah yang dibicarakan.',
                        'Pertanyaan ditujukan ke seluruh kelas lebih dahulu, baru menunjuk salah satu siswa.',
                        'Pemberian waktu berpikir untuk bertanya dan menjawab',
                        'Pendistribusian pertanyaan secara merata di antara para siswa.',
                        'Pemberian tuntunan: 1) Pengungkapan pertanyaan dgn cara lain. 2) Mengajukan pertanyaan lain yg lebih sederhana. 3) Menyederhanakan pertanyaan yg diajukan.'
                    ]
                ];
            case 6:
                return [
                    'A. Penguatan Verbal' => [
                        'Mengucapkan kata-kata benar, bagus, tepat, dan bagus sekali bila murid menjawab/mengajukan pertanyaan.',
                        'Mengucapkan kalimat pekerjaanmu baik sekali, saya senang dengan pekerjaanmu, pekerjaanmu makin lama makin baik, pikir dulu, dan lihat lagi, untuk membesarkan hati dan memberikan dorongan.'
                    ],
                    'B. Penguatan Non Verbal' => [
                        'Penguatan berupa senyuman, anggukan, pandangan yang ramah, atau gerakan badan.',
                        'Penguatan dengan cara mendekati.',
                        'Penguatan dengan sentuhan.',
                        'Penguatan dengan kegiatan yang menyenangkan.',
                        'Penguatan dengan memberikan hadiah yang relevan dan rasional'
                    ]
                ];
            case 7:
                return [
                    'Kegiatan Menutup Pembelajaran' => [
                        'Kemampuan menyimpulkan KBM dengan tepat',
                        'Kemampuan menggunakan kata-kata yang memebesarkan hati siswa',
                        'Kemampuan memberikan evaluasi lisan maupun tulisan',
                        'Kemampuan melakukan evaluasi beberapa aspek dengan berbagai teknik'
                    ]
                ];
            default:
                return [];
        }
    }
}