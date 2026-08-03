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

        TahunAjaran::create([
            'nama' => $request->nama,
            'is_active' => true,
        ]);

        return redirect()->route('admin.kelolaperangkat', ['tahun_ajaran' => $request->nama])->with('success', 'Tahun ajaran berhasil ditambahkan.');
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
