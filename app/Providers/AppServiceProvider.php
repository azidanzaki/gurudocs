<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('admin', fn($user) => $user->role === 'admin');
        Gate::define('guru', fn($user) => $user->role === 'guru');
        Gate::define('kepala', fn($user) => $user->role === 'kepala_sekolah');
    }
}
