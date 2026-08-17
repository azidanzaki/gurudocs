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

        // Sorting
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Ensure only valid columns can be sorted
        $allowedSorts = ['name', 'nip', 'role', 'is_active', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->latest();
        }

        $users = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.kelolauser._table', compact('users'))->render();
        }

        $mapels = Mapel::all();
        $kelas = Kelas::all();
        $hasActiveKepsek = User::where('role', 'kepala_sekolah')->where('is_active', true)->exists();

        return view('admin.kelolauser.index', compact(
            'users',
            'mapels',
            'kelas',
            'hasActiveKepsek'
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
            'nip' => ['required', 'unique:users,nip', 'digits:18'],
            'role' => 'required',
        ]);

        // Enforce single active kepala sekolah
        if ($request->role === 'kepala_sekolah') {
            $hasActiveKepsek = User::where('role', 'kepala_sekolah')->where('is_active', true)->exists();
            if ($hasActiveKepsek) {
                return redirect()->back()->withInput()->with('error', 'Tidak dapat menambahkan Kepala Sekolah baru. Sudah ada Kepala Sekolah yang aktif. Nonaktifkan terlebih dahulu sebelum menambahkan yang baru.');
            }
        }
        
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
            'role' => 'required',
        ]);

        $user->name = $request->name;
        $user->nip = $request->nip;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            // We usually don't update default_password if they change it manually, 
            // but we can if we want to show it. For security, maybe just change the password.
        }

        $user->save();

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