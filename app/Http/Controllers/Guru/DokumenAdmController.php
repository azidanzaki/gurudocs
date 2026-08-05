<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DokumenAdm;
use Illuminate\Http\Request;

class DokumenAdmController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumenAdm::query();

        // SEARCH
        if ($request->search) {

            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // FILTER JENIS
        if ($request->jenis_dokumen) {

            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        // FILTER TAHUN
        if ($request->tahun_dokumen) {
            $query->where('tahun', $request->tahun_dokumen);
        }

        $dokumen = $query->latest()->get();

        $jenisDokumen = DokumenAdm::select('jenis_dokumen')
            ->distinct()
            ->pluck('jenis_dokumen');

        $tahunDokumen = DokumenAdm::select('tahun')
            ->whereNotNull('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('guru.dokumenadm.index', compact(
            'dokumen',
            'jenisDokumen',
            'tahunDokumen'
        ));
    }
    public function show($id)
    {
        $dokumen = DokumenAdm::findOrFail($id);

        return view('guru.dokumenadm.show', compact('dokumen'));
    }
}
