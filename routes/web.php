<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\DokumenAdmController;
use App\Http\Controllers\Guru\RepositoryController;
use App\Http\Controllers\Guru\PerangkatController;
use App\Http\Controllers\Admin\KelolaPerangkatController;
use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KelolaDokumenAdmController;
use App\Http\Controllers\Kepala\KepalaController;
use App\Http\Controllers\Kepala\PenilaianController;
use App\Http\Controllers\ProfilController;

use App\Http\Controllers\Guru\PerangkatGuruController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

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
            return app(KepalaController::class)->index();
        }

        return app(GuruController::class)->index();

    })->name('dashboard');

});


Route::prefix('guru')->middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');

    Route::get('/template-dokumen', [DokumenAdmController::class, 'index'])->name('guru.dokumenadmguru.index');
    Route::get('/template-dokumen/{id}', [DokumenAdmController::class, 'show'])->name('guru.dokumenadmguru.show');

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
    // Route::get('/perangkat', [PerangkatController::class, 'index'])
    //     ->name('guru.perangkat');

    // Route::get('/perangkat/{id}', [PerangkatController::class, 'show'])
    //     ->name('guru.perangkat.show');

    Route::get('/dokumen-admin', [GuruController::class, 'dokumenAdmin'])->name('dokumenadmguru');

    // Subject list
    Route::get('perangkat', [PerangkatController::class, 'index'])
        ->name('guru.perangkat.index');

    // Subject detail → show classes (this is the "siapkan perangkat" destination)
    Route::get('/guru/perangkat/history', [PerangkatController::class, 'history'])->name('guru.perangkat.history');
    Route::get('/guru/perangkat/{mapel}', [PerangkatController::class, 'show'])->name('guru.perangkat.show');
    Route::get('/guru/perangkat/{mapel}/kelas/{kelas}', [PerangkatController::class, 'showKelas'])->name('guru.perangkat.kelas');

    // Template selected → edit form
    Route::get('/guru/perangkat/{mapel}/kelas/{kelas}/template/{template}', [PerangkatGuruController::class, 'edit'])
        ->name('guru.perangkat.edit');

    // Save draft
    Route::post('perangkat/{mapel}/kelas/{kelas}/template/{template}/save', [PerangkatGuruController::class, 'save'])
        ->name('guru.perangkat.save');

    // Submit
    Route::post('perangkat/{mapel}/kelas/{kelas}/template/{template}/submit', [PerangkatGuruController::class, 'submit'])
        ->name('guru.perangkat.submit');

    // Print
    Route::get('perangkat/{perangkatGuru}/print', [PerangkatGuruController::class, 'print'])
        ->name('guru.perangkat.print');

    // Toggle complete status
    Route::post('perangkat/{perangkatGuru}/toggle-complete', [PerangkatGuruController::class, 'toggleComplete'])
        ->name('guru.perangkat.toggleComplete');
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
    Route::put('/admin/users/{id}', [KelolaUserController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [KelolaUserController::class, 'deleteUser'])->name('admin.users.delete');

    // kelola perangkat pembelajaran
    Route::get('/admin/kelola-perangkat', [KelolaPerangkatController::class, 'index'])->name('admin.kelolaperangkat');
    Route::post('/admin/kelola-perangkat/update-tenggat', [KelolaPerangkatController::class, 'updateTenggat'])->name('admin.kelolaperangkat.updateTenggat');
    Route::post('/admin/kelola-perangkat/tahun-ajaran', [KelolaPerangkatController::class, 'storeTahunAjaran'])->name('admin.kelolaperangkat.storeTahunAjaran');

    // kelola dokumen administratif
    Route::get('/admin/kelola-dokumen', [KelolaDokumenAdmController::class, 'index'])->name('admin.dokumenadm.index');
    Route::post('/admin/kelola-dokumen', [KelolaDokumenAdmController::class, 'store'])->name('admin.dokumenadm.store');
    Route::delete('/admin/kelola-dokumen/{id}/delete', [KelolaDokumenAdmController::class, 'delete'])->name('admin.dokumenadm.delete');

    // Profil admin
    Route::get('/admin/profil', [ProfilController::class, 'index'])->name('admin.profil');
});

/*
|--------------------------------------------------------------------------
| KEPALA SEKOLAH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // General profile update route for all roles
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
});

Route::middleware(['auth', 'can:kepala'])->group(function () {

    Route::get('/kepala/dashboard', [KepalaController::class, 'index'])->name('kepala.dashboard');

    Route::get('/kepala/penilaian', [PenilaianController::class, 'index'])->name('kepala.penilaian');
    Route::get('/kepala/penilaian/guru/{id}', [PenilaianController::class, 'showGuru'])->name('kepala.penilaian.show');

    Route::get('/kepala/profil', [ProfilController::class, 'index'])->name('kepala.profil');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';