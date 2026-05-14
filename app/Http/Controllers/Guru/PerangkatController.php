<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Mapel;

class PerangkatController extends Controller
{
    public function index()
    {
        $mapels = Auth::user()->mapels;

        return view('guru.perangkat.index', compact('mapels'));
    }

    public function show($id)
    {
        $mapel = Mapel::with('perangkats')->findOrFail($id);

        return view('guru.perangkat.show', compact('mapel'));
    }
}