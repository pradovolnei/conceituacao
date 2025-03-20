<?php
use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\UserController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/users', UserController::class);
});
