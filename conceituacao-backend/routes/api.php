<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Rotas de autenticação do Breeze (login, register, logout, etc.)
require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    // Rotas protegidas para Perfis
    Route::apiResource('profiles', ProfileController::class);

    // Você pode adicionar mais rotas aqui, se necessário.
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');