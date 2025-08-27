<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rotas de login e logout para o Sanctum
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');

Route::post('/register', [RegisteredUserController::class, 'store']);

// A sua rota csrf-cookie já deve estar aqui
// Se não estiver, adicione-a
Route::get('/sanctum/csrf-cookie', function () {
    return response('OK');
});

// Outras rotas da sua aplicação
Route::get('/', function () {
    return view('welcome');
});

// Rota de fallback para o Vue Router. Deve ser a última rota.
Route::fallback(function () {
    return view('app');
});
