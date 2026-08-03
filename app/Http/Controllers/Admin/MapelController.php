<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mapel;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_mapel', 'like', "%{$search}%");
        }

        $mapels = $query->latest()->paginate(10)->withQueryString();

        return view('admin.mapel.index', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|max:255',
        ]);

        Mapel::create([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|max:255',
        ]);

        $mapel = Mapel::findOrFail($id);
        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil diupdate');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus');
    }
}
