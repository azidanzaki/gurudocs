<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kepala\KepalaController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('dashboardguru');
})->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| GURU ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboardguru');

    Route::get('/repository', [GuruController::class, 'repository'])->name('repositoryguru');

    Route::get('/dokumen', [GuruController::class, 'dokumen'])->name('dokumenguru');

    Route::get('/profil', [GuruController::class, 'profil'])->name('profilguru');

    Route::get('/perangkat', [GuruController::class, 'perangkat'])->name('perangkatguru');

    Route::get('/dokumen-admin', [GuruController::class, 'dokumenAdmin'])->name('dokumenadmguru');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admindashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');

    Route::get('/admin/template', [AdminController::class, 'template'])->name('admin.template');
});

/*
|--------------------------------------------------------------------------
| KEPALA SEKOLAH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:kepala'])->group(function () {

    Route::get('/kepala/dashboard', [KepalaController::class, 'dashboard'])->name('kepala.dashboard');

    Route::get('/kepala/penilaian', [KepalaController::class, 'penilaian'])->name('kepala.penilaian');

    Route::get('/kepala/repository', [KepalaController::class, 'repository'])->name('kepala.repository');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';