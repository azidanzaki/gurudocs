<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\PerangkatGuru;
use App\Models\PerangkatGuruSection;
use App\Models\PerangkatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerangkatGuruController extends Controller
{
    // Step 4: Template selected — open the editable form
    public function edit(Request $request, Mapel $mapel, Kelas $kelas, PerangkatTemplate $template)
    {
        $template->load('sections');

        $semester = $request->get('semester', 1);
        $bab = $request->get('bab', 0);
        $tahun_ajaran = $request->get('tahun_ajaran', \App\Models\TahunAjaran::orderBy('nama', 'desc')->first()->nama ?? '2025/2026');

        $perangkatGuru = PerangkatGuru::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'mapel_id' => $mapel->id,
                'kelas_id' => $kelas->id,
                'perangkat_template_id' => $template->id,
                'tahun_ajaran' => $tahun_ajaran,
                'semester' => $semester,
                'bab' => $bab,
            ],
            [
                'tahun' => now()->year,
                'status' => 'draft',
            ]
        );

        $savedValues = $perangkatGuru
            ->filledSections
            ->pluck('value', 'field_key');

        return view('guru.perangkat.edit', compact(
            'mapel',
            'kelas',
            'template',
            'perangkatGuru',
            'savedValues',
            'tahun_ajaran'
        ));
    }

    // Step 5a: Save as draft
    public function save(Request $request, Mapel $mapel, Kelas $kelas, PerangkatTemplate $template)
    {
        $perangkatGuru = $this->resolvePerangkatGuru($request, $mapel, $kelas, $template);
        abort_if($perangkatGuru->isSubmitted(), 403);

        DB::transaction(function () use ($request, $perangkatGuru, $template) {
            $template->load('sections');
            foreach ($template->sections as $section) {
                PerangkatGuruSection::updateOrCreate(
                    ['perangkat_guru_id' => $perangkatGuru->id, 'perangkat_template_section_id' => $section->id],
                    ['field_key' => $section->field_key, 'value' => $request->input($section->field_key, '')]
                );
            }
        });

        if ($request->ajax()) {
            return response()->json(['message' => 'Draft berhasil disimpan.']);
        }

        return back()->with('success', 'Draft berhasil disimpan.');
    }

    // Step 5b: Submit
    public function submit(Request $request, Mapel $mapel, Kelas $kelas, PerangkatTemplate $template)
    {
        $perangkatGuru = $this->resolvePerangkatGuru($request, $mapel, $kelas, $template);

        abort_if($perangkatGuru->isSubmitted(), 403, 'Perangkat sudah disubmit.');

        $template->load('sections');

        DB::transaction(function () use ($request, $perangkatGuru, $template) {
            foreach ($template->sections as $section) {
                PerangkatGuruSection::updateOrCreate(
                    [
                        'perangkat_guru_id' => $perangkatGuru->id,
                        'perangkat_template_section_id' => $section->id,
                    ],
                    [
                        'field_key' => $section->field_key,
                        'value' => $request->input($section->field_key, ''),
                    ]
                );
            }
        });

        $filled = $perangkatGuru->fresh()->filledSections->pluck('value', 'field_key');
        $missing = $template->sections
            ->where('is_required', true)
            ->filter(fn($s) => empty($filled[$s->field_key]))
            ->pluck('label');

        if ($missing->isNotEmpty()) {
            return back()->withErrors([
                'submit' => 'Kolom berikut wajib diisi: ' . $missing->join(', ') . '.',
            ]);
        }

        $perangkatGuru->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('guru.perangkat.kelas', [$mapel->id, $kelas->id])
            ->with('success', 'Perangkat berhasil disubmit!');
    }

    // Step 5c: Reset
    public function reset(Request $request, Mapel $mapel, Kelas $kelas, PerangkatTemplate $template)
    {
        $perangkatGuru = $this->resolvePerangkatGuru($request, $mapel, $kelas, $template);

        DB::transaction(function () use ($perangkatGuru) {
            $perangkatGuru->filledSections()->delete();
            $perangkatGuru->delete();
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('guru.perangkat.kelas', [$mapel->id, $kelas->id])
            ]);
        }

        return redirect()->route('guru.perangkat.kelas', [$mapel->id, $kelas->id])
            ->with('success', 'Perangkat berhasil direset.');
    }

    // Step 6: Print
    public function print(PerangkatGuru $perangkatGuru)
    {
        abort_if(
            $perangkatGuru->user_id !== Auth::id() && 
            !in_array(Auth::user()->role, ['kepala_sekolah', 'admin']), 
            403
        );

        $perangkatGuru->load([
            'mapel',
            'kelas',
            'guru',
            'template.sections',
            'filledSections',
        ]);

        $savedValues = $perangkatGuru->filledSections->pluck('value', 'field_key');

        return view('guru.perangkat.print', compact('perangkatGuru', 'savedValues'));
    }

    public function toggleComplete(Request $request, PerangkatGuru $perangkatGuru)
    {
        abort_if($perangkatGuru->user_id !== Auth::id(), 403);

        if ($request->has('force_complete')) {
            $perangkatGuru->is_completed = true;
            $perangkatGuru->status = 'submitted';
            $perangkatGuru->submitted_at = now();
        } else {
            $perangkatGuru->is_completed = !$perangkatGuru->is_completed;
        }
        
        $perangkatGuru->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'is_completed' => $perangkatGuru->is_completed,
            ]);
        }

        return back()->with('success', 'Status penyelesaian perangkat diperbarui.');
    }

    private function resolvePerangkatGuru(Request $request, Mapel $mapel, Kelas $kelas, PerangkatTemplate $template): PerangkatGuru
    {
        $semester = $request->get('semester', 1);
        $bab = $request->get('bab', 0);
        $tahun_ajaran = $request->get('tahun_ajaran', \App\Models\TahunAjaran::orderBy('nama', 'desc')->first()->nama ?? '2025/2026');

        return PerangkatGuru::where([
            'user_id' => Auth::id(),
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'perangkat_template_id' => $template->id,
            'tahun_ajaran' => $tahun_ajaran,
            'semester' => $semester,
            'bab' => $bab,
        ])->firstOrFail();
    }
}