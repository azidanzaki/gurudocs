<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PkgSettingController extends Controller
{
    public function index()
    {
        $kategoris = \App\Models\PkgKategori::with('indikators')->orderBy('aspek')->orderBy('id')->get();
        $groupedKategoris = $kategoris->groupBy('aspek');

        return view('kepala.pkg_settings.index', compact('groupedKategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'aspek' => 'required|integer|min:1|max:7',
            'nama' => 'required|string|max:255',
        ]);

        \App\Models\PkgKategori::create([
            'aspek' => $request->aspek,
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Kategori (Judul) berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = \App\Models\PkgKategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Kategori (Judul) berhasil diperbarui.');
    }

    public function destroyKategori($id)
    {
        $kategori = \App\Models\PkgKategori::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori (Judul) berhasil dihapus.');
    }

    public function storeIndikator(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:pkg_kategoris,id',
            'nama' => 'required|string|max:255',
        ]);

        \App\Models\PkgIndikator::create([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Indikator berhasil ditambahkan.');
    }

    public function updateIndikator(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $indikator = \App\Models\PkgIndikator::findOrFail($id);
        $indikator->update([
            'nama' => $request->nama,
        ]);

        return redirect()->back()->with('success', 'Indikator berhasil diperbarui.');
    }

    public function destroyIndikator($id)
    {
        $indikator = \App\Models\PkgIndikator::findOrFail($id);
        $indikator->delete();

        return redirect()->back()->with('success', 'Indikator berhasil dihapus.');
    }
}
