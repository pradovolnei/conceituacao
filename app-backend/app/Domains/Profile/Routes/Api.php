<?php
use Illuminate\Support\Facades\Route;
use App\Domains\Profile\Controllers\ProfileController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/profiles', ProfileController::class);
});
