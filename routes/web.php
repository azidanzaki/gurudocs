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
use App\Http\Controllers\Admin\DataMasterController;
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


Route::get('/profile', function () {

    $user = auth()->user();

    if ($user->role == 'kepala_sekolah') {

        return redirect()->route('kepala.profil');

    } elseif ($user->role == 'guru') {

        return redirect()->route('guru.profil');

    } elseif ($user->role == 'admin') {

        return redirect()->route('admin.profil');

    }

    abort(403);

})->middleware('auth')->name('profile');

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

    // Reset
    Route::post('perangkat/{mapel}/kelas/{kelas}/template/{template}/reset', [PerangkatGuruController::class, 'reset'])
        ->name('guru.perangkat.reset');

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
    Route::post('/admin/kelola-perangkat/{id}/reopen', [KelolaPerangkatController::class, 'reopen'])->name('admin.kelolaperangkat.reopen');

    // kelola dokumen administratif
    Route::get('/admin/kelola-dokumen', [KelolaDokumenAdmController::class, 'index'])->name('admin.dokumenadm.index');
    Route::post('/admin/kelola-dokumen', [KelolaDokumenAdmController::class, 'store'])->name('admin.dokumenadm.store');
    Route::delete('/admin/kelola-dokumen/{id}/delete', [KelolaDokumenAdmController::class, 'delete'])->name('admin.dokumenadm.delete');

    // mapelkelas (mapel, kelas, penugasan guru)
    Route::get('/admin/mapelkelas', [DataMasterController::class, 'index'])->name('admin.mapelkelas.index');
    Route::post('/admin/mapelkelas/mapel', [DataMasterController::class, 'storeMapel'])->name('admin.mapelkelas.mapel.store');
    Route::put('/admin/mapelkelas/mapel/{id}', [DataMasterController::class, 'updateMapel'])->name('admin.mapelkelas.mapel.update');
    Route::delete('/admin/mapelkelas/mapel/{id}', [DataMasterController::class, 'destroyMapel'])->name('admin.mapelkelas.mapel.destroy');
    
    Route::post('/admin/mapelkelas/kelas', [DataMasterController::class, 'storeKelas'])->name('admin.mapelkelas.kelas.store');
    Route::put('/admin/mapelkelas/kelas/{id}', [DataMasterController::class, 'updateKelas'])->name('admin.mapelkelas.kelas.update');
    Route::delete('/admin/mapelkelas/kelas/{id}', [DataMasterController::class, 'destroyKelas'])->name('admin.mapelkelas.kelas.destroy');

    Route::post('/admin/mapelkelas/guru/{user}/penugasan', [DataMasterController::class, 'storePenugasan'])->name('admin.mapelkelas.penugasan.store');
    Route::delete('/admin/mapelkelas/penugasan/{id}', [DataMasterController::class, 'destroyPenugasan'])->name('admin.mapelkelas.penugasan.destroy');

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
    Route::get('/kepala/penilaian/guru/{id}/kelengkapan', [PenilaianController::class, 'kelengkapanDokumen'])->name('kepala.penilaian.kelengkapan');
    Route::get('/kepala/penilaian/guru/{id}/pkg', [PenilaianController::class, 'pkg'])->name('kepala.penilaian.pkg');
    Route::get('/kepala/penilaian/guru/{id}/pkg/cetak', [PenilaianController::class, 'cetakPkg'])->name('kepala.penilaian.cetakPkg');
    Route::get('/kepala/penilaian/guru/{id}/start/{aspect}', [PenilaianController::class, 'start'])->name('kepala.penilaian.start');
    Route::post('/kepala/penilaian/guru/{id}/store/{aspect}', [PenilaianController::class, 'store'])->name('kepala.penilaian.store');

    Route::get('/kepala/profil', [ProfilController::class, 'index'])->name('kepala.profil');

    // PKG Settings
    Route::get('/kepala/pkg-settings', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'index'])->name('kepala.pkg_settings.index');
    Route::post('/kepala/pkg-settings/kategori', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'storeKategori'])->name('kepala.pkg_settings.kategori.store');
    Route::put('/kepala/pkg-settings/kategori/{id}', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'updateKategori'])->name('kepala.pkg_settings.kategori.update');
    Route::delete('/kepala/pkg-settings/kategori/{id}', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'destroyKategori'])->name('kepala.pkg_settings.kategori.destroy');
    
    Route::post('/kepala/pkg-settings/indikator', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'storeIndikator'])->name('kepala.pkg_settings.indikator.store');
    Route::put('/kepala/pkg-settings/indikator/{id}', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'updateIndikator'])->name('kepala.pkg_settings.indikator.update');
    Route::delete('/kepala/pkg-settings/indikator/{id}', [\App\Http\Controllers\Kepala\PkgSettingController::class, 'destroyIndikator'])->name('kepala.pkg_settings.indikator.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';