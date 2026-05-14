<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class RepositoryController extends Controller
{
    public function index()
    {
        $repositories = Repository::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('guru.repository.index', compact('repositories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required',
            'semester' => 'required',
            'judul' => 'required',
            'deskripsi' => 'nullable',
            'foto_kegiatan' => 'required|image|mimes:jpg,jpeg,png',
            'sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $foto = $request->file('foto_kegiatan')
            ->store('repository/kegiatan', 'public');

        $sertifikat = null;

        if ($request->hasFile('sertifikat')) {
            $sertifikat = $request->file('sertifikat')
                ->store('repository/sertifikat', 'public');
        }

        Repository::create([
            'user_id' => Auth::id(),
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto_kegiatan' => $foto,
            'sertifikat' => $sertifikat,
        ]);

        return back()->with('success', 'Kegiatan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $repo = Repository::findOrFail($id);

        $request->validate([
            'tahun_ajaran' => 'required',
            'semester' => 'required',
            'judul' => 'required',
            'deskripsi' => 'nullable',
            'foto_kegiatan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('foto_kegiatan')) {

            if ($repo->foto_kegiatan) {
                Storage::disk('public')->delete($repo->foto_kegiatan);
            }

            $repo->foto_kegiatan = $request->file('foto_kegiatan')
                ->store('repository/kegiatan', 'public');
        }

        if ($request->hasFile('sertifikat')) {

            if ($repo->sertifikat) {
                Storage::disk('public')->delete($repo->sertifikat);
            }

            $repo->sertifikat = $request->file('sertifikat')
                ->store('repository/sertifikat', 'public');
        }

        $repo->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto_kegiatan' => $repo->foto_kegiatan,
            'sertifikat' => $repo->sertifikat,
        ]);

        return back()->with('success', 'Kegiatan berhasil diupdate');
    }

    public function destroy($id)
    {
        $repo = Repository::findOrFail($id);

        if ($repo->foto_kegiatan) {
            Storage::disk('public')->delete($repo->foto_kegiatan);
        }

        if ($repo->sertifikat) {
            Storage::disk('public')->delete($repo->sertifikat);
        }

        $repo->delete();

        return back()->with('success', 'Kegiatan berhasil dihapus');
    }
    public function viewSertifikat($id)
    {
        $repo = Repository::findOrFail($id);

        $path = storage_path('app/public/' . $repo->sertifikat);

        return response()->file($path);
    }
}