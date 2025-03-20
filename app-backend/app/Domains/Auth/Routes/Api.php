<?php
use Illuminate\Support\Facades\Route;
use App\Domains\Auth\Controllers\AuthController;

Route::post('/auth/login', [AuthController::class, 'authenticate']);
