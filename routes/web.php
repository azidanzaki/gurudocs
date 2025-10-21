<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboardguru', [GuruController::class, 'index'])->name('dashboard');

