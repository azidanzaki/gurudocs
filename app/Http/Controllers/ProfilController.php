<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PerangkatGuru;
use App\Models\Repository;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch recent activities
        $activities = collect();
        
        // 1. Get PerangkatGuru activities
        if ($user->role === 'guru') {
            $perangkats = PerangkatGuru::where('user_id', $user->id)
                ->with('template', 'mapel', 'kelas')
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
                
            foreach ($perangkats as $p) {
                $status = $p->is_completed ? 'menyelesaikan' : 'menyimpan draf';
                $keterangan = "Telah $status perangkat <b>" . ($p->template->nama_perangkat ?? 'Perangkat') . "</b> untuk mapel " . ($p->mapel->nama_mapel ?? '') . " kelas " . ($p->kelas->nama_kelas ?? '') . ".";
                
                $activities->push([
                    'type' => 'perangkat',
                    'icon' => $p->is_completed ? 'fas fa-check-circle bg-success' : 'fas fa-edit bg-warning',
                    'description' => $keterangan,
                    'time' => $p->updated_at
                ]);
            }
            
            // 2. Get Repository activities
            $repos = Repository::where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
                
            foreach ($repos as $r) {
                $activities->push([
                    'type' => 'repository',
                    'icon' => 'fas fa-images bg-info',
                    'description' => "Telah mengunggah/mengedit kegiatan repository <b>" . $r->nama_kegiatan . "</b>.",
                    'time' => $r->updated_at
                ]);
            }
        } elseif ($user->role === 'admin') {
            // 1. DokumenAdm activities
            $dokumens = \App\Models\DokumenAdm::orderBy('updated_at', 'desc')->take(10)->get();
            foreach ($dokumens as $d) {
                $activities->push([
                    'type' => 'dokumen',
                    'icon' => 'fas fa-file-alt bg-primary',
                    'description' => "Memperbarui dokumen administrasi <b>" . $d->nama_dokumen . "</b>.",
                    'time' => $d->updated_at
                ]);
            }

            // 2. PerangkatTemplate activities
            $templates = \App\Models\PerangkatTemplate::orderBy('created_at', 'desc')->take(5)->get();
            foreach ($templates as $t) {
                $activities->push([
                    'type' => 'template',
                    'icon' => 'fas fa-book bg-success',
                    'description' => "Menambahkan template perangkat pembelajaran <b>" . $t->nama_perangkat . "</b>.",
                    'time' => $t->created_at
                ]);
            }
        } elseif ($user->role === 'kepala_sekolah') {
            // PenilaianKinerja activities
            $penilaians = \App\Models\PenilaianKinerja::where('penilai_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->take(15)
                ->get();
            
            foreach ($penilaians as $p) {
                $guru = \App\Models\User::find($p->guru_id);
                $status = $p->status === 'submitted' ? 'Menyelesaikan' : 'Memperbarui';
                $activities->push([
                    'type' => 'penilaian',
                    'icon' => $p->status === 'submitted' ? 'fas fa-check-double bg-success' : 'fas fa-edit bg-info',
                    'description' => "$status penilaian kinerja untuk guru <b>" . ($guru ? $guru->name : 'Unknown') . "</b> pada Aspek {$p->aspek}.",
                    'time' => $p->updated_at
                ]);
            }
        }

        // Sort all activities by time desc and take top 15
        $activities = $activities->sortByDesc('time')->take(15);

        // View yang sama digunakan oleh Admin, Guru, dan Kepsek
        return view('guru.profil.index', compact('activities'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
            }
            $file = $request->file('foto');
            $path = $file->store('profil', 'public');
            $user->foto = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
