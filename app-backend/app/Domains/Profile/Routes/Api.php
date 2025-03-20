<?php
use Illuminate\Support\Facades\Route;
use App\Domains\Profile\Controllers\ProfileController;
use App\Domains\Profile\Controllers\ProfileUserController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['guard.administrator'])->group(function () {
        Route::apiResource('/profiles', ProfileController::class);

        Route::post('/profiles/attach', [ProfileUserController::class, 'attachProfile']);
        Route::post('/profiles/detach', [ProfileUserController::class, 'detachProfile']);
        Route::get('/profiles/users/{id}', [ProfileUserController::class, 'getProfilesByUser']);
    });
});
