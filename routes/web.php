<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\DokumenAdmController;
use App\Http\Controllers\Guru\DokumenNonAdmController;
use App\Http\Controllers\Guru\RepositoryController;
use App\Http\Controllers\Guru\PerangkatController;
use App\Http\Controllers\Admin\KelolaPerangkatController;
use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\KelolaDokumenAdmController;
use App\Http\Controllers\Admin\KelolaDokumenNonAdmController;
use App\Http\Controllers\Kepala\KepalaController;
use App\Http\Controllers\Kepala\PenilaianController;
use App\Http\Controllers\ProfilController;


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
    return redirect()->route('guru.dashboard');
})->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| GURU ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('auth()->user()->role/dashboard', function () {

        if (auth()->user()->role == 'admin') {
            return app(AdminController::class)->dashboard();
        }
        if (auth()->user()->role == 'kepala_sekolah') {
            return app(KepalaController::class)->dashboard();
        }

        return app(GuruController::class)->index();

    })->name('dashboard');

});


Route::prefix('guru')->middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');

    //dokumen non adm
    Route::get('/dokumen-non-administrasi', [DokumenNonAdmController::class, 'index'])->name('guru.dokumennonadm');

    // dokumen adm
    Route::get('/dokumen-administasi', [DokumenAdmController::class, 'index'])->name('guru.dokumenadmguru');

    // profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('guru.profil');

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

    Route::get('/dokumen-admin', [GuruController::class, 'dokumenAdmin'])->name('dokumenadmguru');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:admin'])->group(function () {

    // dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // kelola user
    Route::get('/admin/users', [KelolaUserController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [KelolaUserController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [KelolaUserController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}',[KelolaUserController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}',[KelolaUserController::class, 'deleteUser'])->name('admin.users.delete');

    // kelola template
    Route::get('/admin/template', [TemplateController::class, 'createUser'])->name('admin.template');

    // kelola perangkat pembelajaran
    Route::get('/admin/kelola-perangkat', [KelolaPerangkatController::class, 'index'])->name('admin.kelolaperangkat');

    // kelola dokumen administratif
    Route::get('/admin/kelola-dokumen-administratif', [KelolaDokumenAdmController::class, 'index'])->name('admin.dokumenadm');

    // kelola dokumen non administratif
    Route::get('admin/kelola-dokumen-non-administrasi', [KelolaDokumenNonAdmController::class, 'index'])->name('admin.dokumennonadm');

    // Profil admin
    Route::get('/admin/profil', [ProfilController::class, 'index'])->name('admin.profil');
});

/*
|--------------------------------------------------------------------------
| KEPALA SEKOLAH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:kepala'])->group(function () {

    Route::get('/kepala/dashboard', [KepalaController::class, 'index'])->name('kepala.dashboard');

    Route::get('/kepala/penilaian', [PenilaianController::class, 'index'])->name('kepala.penilaian');

    Route::get('/kepala/profil', [ProfilController::class, 'index'])->name('kepala.profil');

    //dokumen non admin
    Route::get('/kepala/dokumen-non-administrasi', [DokumenNonAdmController::class, 'index'])->name('kepala.dokumennonadm');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';