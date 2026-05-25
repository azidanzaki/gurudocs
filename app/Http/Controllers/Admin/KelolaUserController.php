<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Mapel;
use App\Models\Kelas;

class KelolaUserController extends Controller
{
    public function users()
    {
        $users = User::latest()->get();

        $mapels = Mapel::all();

        $kelas = Kelas::all();

        return view('admin.kelolauser.index', compact(
            'users',
            'mapels',
            'kelas'
        ));
    }

    // FORM TAMBAH USER
    public function createUser()
    {
        return view('admin.kelolauser.index.user-create');
    }

    // SIMPAN USER
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'nip' => 'required|unique:users,nip',
            'role' => 'required',
        ]);

        $defaultPassword = $request->nip;

        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
            'password' => Hash::make($defaultPassword),
            'default_password' => $defaultPassword,
        ]);

        // RELASI
        $mapels = $request->mapels ?? [];
        $kelas = $request->kelas ?? [];
        $inserts = [];
        foreach ($mapels as $i => $mapel_id) {
            if (!empty($mapel_id) && !empty($kelas[$i])) {
                $inserts[] = [
                    'user_id' => $user->id,
                    'mapel_id' => $mapel_id,
                    'kelas_id' => $kelas[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($inserts)) {
            \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->insert($inserts);
        }

        $user->waliKelas()->sync($request->wali_kelas ?? []);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->where('user_id', $user->id)->delete();
        $mapels = $request->mapels ?? [];
        $kelas = $request->kelas ?? [];
        $inserts = [];
        foreach ($mapels as $i => $mapel_id) {
            if (!empty($mapel_id) && !empty($kelas[$i])) {
                $inserts[] = [
                    'user_id' => $user->id,
                    'mapel_id' => $mapel_id,
                    'kelas_id' => $kelas[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if (!empty($inserts)) {
            \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->insert($inserts);
        }

        $user->waliKelas()->sync($request->wali_kelas ?? []);

        $request->validate([
            'name' => 'required',
            'nip' => 'required',
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil diupdate');
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();

        $statusMessage = $user->is_active ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan';

        return redirect()
            ->route('admin.users')
            ->with('success', $statusMessage);
    }
}
