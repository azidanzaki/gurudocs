<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\User;

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
        return view('kepala.penilaian.show', compact('guru'));
    }
}