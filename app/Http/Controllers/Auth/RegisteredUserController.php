<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nip' => ['required', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'nip' => $request->nip, // atau ganti ke nip biar konsisten
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}
