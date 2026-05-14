<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kepala\KepalaController;
use App\Http\Controllers\Guru\PerangkatController;

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

    Route::get('/dokumen', [GuruController::class, 'dokumen'])->name('dokumenguru');

    Route::get('/profil', [GuruController::class, 'profil'])->name('profilguru');

    Route::get('/perangkat', [GuruController::class, 'perangkat'])->name('perangkatguru');

    Route::get('/dokumen-admin', [GuruController::class, 'dokumenAdmin'])->name('dokumenadmguru');
});

use App\Http\Controllers\Guru\RepositoryController;

Route::prefix('guru')->middleware(['auth'])->group(function () {

    // Repository Routes
    Route::get('/repository', [RepositoryController::class, 'index'])
        ->name('guru.repository');

    Route::get('/repository/sertifikat/{id}', [RepositoryController::class, 'viewSertifikat'])
        ->name('guru.repository.sertifikat');

    Route::post('/repository/store', [RepositoryController::class, 'store'])
        ->name('guru.repository.store');

    Route::put('/repository/update/{id}', [RepositoryController::class, 'update'])
        ->name('guru.repository.update');

    Route::delete('/repository/delete/{id}', [RepositoryController::class, 'destroy'])
        ->name('guru.repository.delete');

    // Perangkat Routes
    Route::get('/perangkat', [PerangkatController::class, 'index'])
        ->name('guru.perangkat');

    Route::get('/perangkat/{id}', [PerangkatController::class, 'show'])
        ->name('guru.perangkat.show');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admindashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');

    Route::get('/admin/users/create', [AdminController::class, 'createUser'])
        ->name('admin.users.create');

    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])
        ->name('admin.users.store');

    Route::get('/admin/template', [AdminController::class, 'template'])->name('admin.template');
    Route::put(
        '/admin/users/{id}',
        [AdminController::class, 'updateUser']
    )->name('admin.users.update');

    Route::delete(
        '/admin/users/{id}',
        [AdminController::class, 'deleteUser']
    )->name('admin.users.delete');
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

require __DIR__ . '/auth.php';