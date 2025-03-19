<?php
use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\UserController;

/*Route::get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


Route::get('/users', [UserController::class, 'index']);
