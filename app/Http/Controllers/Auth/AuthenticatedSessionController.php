<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nip' => ['required'],
            'password' => ['required'],
        ]);

        if (
            Auth::attempt([
                'nip' => $request->nip,
                'password' => $request->password
            ])
        ) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'nip' => 'Akun Anda telah dinonaktifkan. Jika ingin masuk, minta admin untuk mengaktifkan akun Anda.',
                ])->onlyInput('nip');
            }

            $request->session()->regenerate();

            // REDIRECT BERDASARKAN ROLE
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role == 'kepala_sekolah') {
                return redirect('/kepala/dashboard');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->onlyInput('nip');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
