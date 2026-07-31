<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Gate Role
        Gate::define('admin', fn($user) => $user->role === 'admin');

        Gate::define('guru', fn($user) => $user->role === 'guru');

        Gate::define('kepala', fn($user) => $user->role === 'kepala_sekolah');



        // Dynamic AdminLTE Profile URL
        if (Auth::check()) {

            $role = Auth::user()->role;


            if ($role === 'kepala_sekolah') {

                config([
                    'adminlte.profile_url' => 'kepala/profil'
                ]);

            } elseif ($role === 'guru') {

                config([
                    'adminlte.profile_url' => 'guru/profil'
                ]);

            } elseif ($role === 'admin') {

                config([
                    'adminlte.profile_url' => 'admin/profil'
                ]);

            }

        }

    }
}