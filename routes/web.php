<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruDokumen;
use App\Http\Controllers\GuruHistory;
use App\Http\Controllers\GuruProfil;
use App\Http\Controllers\GuruTugas;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboardguru', [GuruController::class, 'index'])->name('dashboardguru');
Route::get('/dokumenguru', [GuruDokumen::class, 'index'])->name('dokumenguru');
Route::get('/historyguru', [GuruHistory::class, 'index'])->name('historyguru');
Route::get('/profilguru', [GuruProfil::class, 'index'])->name('profilguru');
Route::get('/tugasguru', [GuruTugas::class, 'index'])->name('tugasguru');


