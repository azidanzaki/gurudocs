<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mapel;
use App\Models\Kelas;

class AdminController extends Controller
{
    // DASHBOARD
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // LIST USER
    public function users()
    {
        $users = User::latest()->get();

        $mapels = Mapel::all();

        $kelas = Kelas::all();

        return view('admin.users', compact(
            'users',
            'mapels',
            'kelas'
        ));
    }

    // FORM TAMBAH USER
    public function createUser()
    {
        return view('admin.users-create');
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
        $user->mapels()->sync($request->mapels ?? []);

        $user->kelas()->sync($request->kelas ?? []);

        $user->waliKelas()->sync($request->wali_kelas ?? []);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->mapels()->sync($request->mapels ?? []);

        $user->kelas()->sync($request->kelas ?? []);

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

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil dihapus');
    }

    // TEMPLATE
    public function template()
    {
        return view('admin.template');
    }
}