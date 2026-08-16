<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenAdm;
use Illuminate\Http\Request;

class KelolaDokumenAdmController extends Controller
{
    public function index(Request $request)
    {
        $query = DokumenAdm::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('jenis_dokumen', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Sorting
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Ensure only valid columns can be sorted
        $allowedSorts = ['judul', 'jenis_dokumen', 'tahun', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        $dokumen = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.keloladokumenadm._table', compact('dokumen'))->render();
        }

        return view('admin.keloladokumenadm.index', compact('dokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'jenis_dokumen' => 'required',
            'tahun' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $judulSlug = str()->slug($request->judul);
        $namaFile = time() . '-' . $judulSlug . '.' . $ext;

        $storedPath = $file->storeAs('dokumenadm', $namaFile, 'public');

        $wordPath = null;
        $pdfPath = null;

        if ($ext == 'pdf') {
            $pdfPath = $storedPath;
        } else {
            $wordPath = $storedPath;
            $fullPath = storage_path('app/public/' . $storedPath);
            $outputDir = storage_path('app/public/dokumenadm/pdf');

            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            shell_exec('"C:\Program Files\LibreOffice\program\soffice.exe" --headless --convert-to pdf "' . $fullPath . '" --outdir "' . $outputDir . '"');

            $pdfFileName = pathinfo($namaFile, PATHINFO_FILENAME) . '.pdf';
            $pdfFullPath = $outputDir . '/' . $pdfFileName;

            if (file_exists($pdfFullPath)) {
                $pdfPath = 'dokumenadm/pdf/' . $pdfFileName;
            }
        }

        DokumenAdm::create([
            'judul' => $request->judul,
            'jenis_dokumen' => $request->jenis_dokumen,
            'tahun' => $request->tahun,
            'file_word' => $wordPath,
            'file_pdf' => $pdfPath,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Dokumen berhasil diupload');
    }
    
    public function delete($id)
    {
        $dokumen = DokumenAdm::findOrFail($id);

        if ($dokumen->file_word && \Storage::disk('public')->exists($dokumen->file_word)) {
            \Storage::disk('public')->delete($dokumen->file_word);
        }

        if ($dokumen->file_pdf && \Storage::disk('public')->exists($dokumen->file_pdf)) {
            \Storage::disk('public')->delete($dokumen->file_pdf);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }
}
