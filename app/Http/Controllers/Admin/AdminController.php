<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\DokumenAdm;

class AdminController extends Controller
{
    // DASHBOARD
    public function dashboard()
    {
        $totalGuru = User::where('role', 'guru')->count();
        $totalMapel = Mapel::count();
        $totalKelas = Kelas::count();
        $totalDokumen = DokumenAdm::count();

        $recentGurus = User::where('role', 'guru')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalGuru',
            'totalMapel',
            'totalKelas',
            'totalDokumen',
            'recentGurus'
        ));
    }
}