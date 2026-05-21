<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenAdm;
use Illuminate\Http\Request;

class KelolaDokumenAdmController extends Controller
{
    public function index()
    {
        $dokumen = DokumenAdm::latest()->get();

        return view('admin.keloladokumenadm.index', compact('dokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'jenis_dokumen' => 'required',
            'file' => 'required|mimes:pdf,doc,docx|max:20480',
        ]);

        $file = $request->file('file');

        // EXTENSION FILE
        $ext = strtolower($file->getClientOriginalExtension());

        // NAMA FILE RAPI
        $judulSlug = str()->slug($request->judul);

        $namaFile = time() . '-' . $judulSlug . '.' . $ext;

        // SIMPAN FILE ASLI
        $storedPath = $file->storeAs(
            'dokumenadm',
            $namaFile,
            'public'
        );

        $wordPath = null;
        $pdfPath = null;

        // =========================
        // JIKA FILE PDF
        // =========================

        if ($ext == 'pdf') {

            $pdfPath = $storedPath;
        }

        // =========================
        // JIKA FILE WORD
        // =========================
        else {

            $wordPath = $storedPath;

            $fullPath = storage_path(
                'app/public/' . $storedPath
            );

            $outputDir = storage_path(
                'app/public/dokumenadm/pdf'
            );

            // PASTIKAN FOLDER PDF ADA
            if (!file_exists($outputDir)) {

                mkdir($outputDir, 0777, true);
            }

            // CONVERT WORD -> PDF
            shell_exec(
                '"C:\Program Files\LibreOffice\program\soffice.exe" ' .
                '--headless --convert-to pdf "' .
                $fullPath .
                '" --outdir "' .
                $outputDir .
                '"'
            );

            // NAMA PDF
            $pdfFileName =
                pathinfo($namaFile, PATHINFO_FILENAME) . '.pdf';

            // PATH PDF
            $pdfFullPath =
                $outputDir . '/' . $pdfFileName;

            // CEK PDF BERHASIL DIBUAT
            if (file_exists($pdfFullPath)) {

                $pdfPath = 'dokumenadm/pdf/' . $pdfFileName;
            }
        }

        // SIMPAN DATABASE
        DokumenAdm::create([

            'judul' => $request->judul,

            'jenis_dokumen' => $request->jenis_dokumen,

            'file_word' => $wordPath,

            'file_pdf' => $pdfPath,

            'created_by' => auth()->id(),

        ]);

        return back()->with(
            'success',
            'Dokumen berhasil diupload'
        );
    }
    public function delete($id)
    {
        $dokumen = DokumenAdm::findOrFail($id);

        // HAPUS FILE WORD
        if (
            $dokumen->file_word &&
            \Storage::disk('public')->exists($dokumen->file_word)
        ) {

            \Storage::disk('public')->delete($dokumen->file_word);
        }

        // HAPUS FILE PDF
        if (
            $dokumen->file_pdf &&
            \Storage::disk('public')->exists($dokumen->file_pdf)
        ) {

            \Storage::disk('public')->delete($dokumen->file_pdf);
        }

        $dokumen->delete();

        return back()->with(
            'success',
            'Dokumen berhasil dihapus'
        );
    }
}