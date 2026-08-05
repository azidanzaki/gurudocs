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
    public function users(Request $request)
{
    $query = User::query();

    // Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%");
        });
    }

    $users = $query->latest()->paginate(10)->withQueryString();

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


        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

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
