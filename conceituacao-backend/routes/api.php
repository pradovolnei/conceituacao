<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

// As rotas abaixo precisam de autenticação via Sanctum.
Route::middleware(['auth:sanctum'])->group(function () {
    // Rota única e correta para obter os dados do usuário logado e seus perfis.
    Route::get('/user', function (Request $request) {
        return $request->user()->load('profiles');
    });

    // Rotas protegidas APENAS para usuários com perfil 'Administrador'.
    // O middleware 'admin' verifica se o usuário autenticado é um administrador.
    Route::middleware(['admin'])->group(function () {
        // Rotas para Perfis
        Route::apiResource('profiles', ProfileController::class);

        // Rotas para Usuários
        Route::apiResource('users', UserController::class);

        // Rotas para associação/desassociação de perfis a usuários
        // Aqui, a rota deve ser PATCH ou PUT, já que está atualizando um relacionamento.
        Route::patch('users/{user}/profiles/attach', [UserController::class, 'attachProfiles']);
        Route::patch('users/{user}/profiles/detach', [UserController::class, 'detachProfiles']);
    });
});
