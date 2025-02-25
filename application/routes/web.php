<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileControllerApp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/perfil', [ProfileControllerApp::class, 'perfil']);
Route::get('/search',[ProfileControllerApp::class, 'search'])->name('perfil.busca');


require __DIR__.'/auth.php';


