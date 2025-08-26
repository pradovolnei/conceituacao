<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// Rotas de autenticação do Breeze (login, register, logout, etc.)
require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    // Rotas protegidas para Perfis
    Route::apiResource('profiles', ProfileController::class);

    // Rotas para Usuários
    Route::apiResource('users', UserController::class);

    // Rotas para associação/desassociação de perfis a usuários
    Route::post('users/{user}/profiles/attach', [UserController::class, 'attachProfiles']);
    Route::post('users/{user}/profiles/detach', [UserController::class, 'detachProfiles']);

    Route::get('/user', function (Request $request) {
        return $request->user()->load('profiles'); // Carrega os perfis do usuário logado
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');