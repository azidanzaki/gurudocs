<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruDokumen;
use App\Http\Controllers\GuruHistory;
use App\Http\Controllers\GuruProfil;
use App\Http\Controllers\GuruTugas;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard bawaan Breeze
Route::get('/dashboard', function () {
    return redirect()->route('dashboardguru');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua route guru harus login dulu
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboardguru', [GuruController::class, 'index'])->name('dashboardguru');

    Route::get('/dokumenguru', [GuruDokumen::class, 'index'])->name('dokumenguru');

    Route::get('/historyguru', [GuruHistory::class, 'index'])->name('historyguru');

    Route::get('/profilguru', [GuruProfil::class, 'index'])->name('profilguru');

    Route::get('/tugasguru', [GuruTugas::class, 'index'])->name('tugasguru');

    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';