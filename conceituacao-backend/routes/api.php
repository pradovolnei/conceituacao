<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Rota de login para a API, sem middleware de autenticação, para permitir o acesso
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Rota de registro para a API, também sem middleware
Route::post('/register', [RegisteredUserController::class, 'store']);

// Agrupa todas as rotas que requerem autenticação via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Rota para o logout, que precisa de autenticação para ser acessada
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Deslogado com sucesso.']);
    });

    // Rota para obter os dados do usuário autenticado e seus perfis
    // A rota "/user" é padrão do Sanctum, é bom mantê-la.
    Route::get('/user', function (Request $request) {
        return $request->user()->load('profiles');
    });

    // Rotas protegidas para Perfis, acessíveis apenas por administradores
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('profiles', ProfileController::class);
        Route::apiResource('users', UserController::class);

        // Rotas para associação/desassociação de perfis
        Route::patch('users/{user}/profiles/attach', [UserController::class, 'attachProfiles']);
        Route::patch('users/{user}/profiles/detach', [UserController::class, 'detachProfiles']);
    });
});
