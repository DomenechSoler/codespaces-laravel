<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAuthenticated;
use App\Http\Middleware\IsUserAdmin;
use App\Http\Middleware\IsUserAuth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetsController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// GESTIÓ D'USUARIS (ADMIN)
Route::middleware([IsAdmin::class])->group(function () {
    Route::get('users', [AuthController::class, 'getUsers']);
    Route::get('users/{id}', [AuthController::class, 'getUserById']);
    Route::put('users/{id}', [AuthController::class, 'updateUser']);
    Route::delete('users/{id}', [AuthController::class, 'deleteUser']);
});

// GESTIÓ DE MASCOTES (USUARI AUTENTICAT)
Route::middleware([IsUserAuth::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'getUser']);
    Route::get('pets', [PetsController::class, 'index']);
    Route::post('pets', [PetsController::class, 'store']);
    Route::put('pets/{id}', [PetsController::class, 'update']);
    Route::patch('pets/{id}', [PetsController::class, 'partialUpdate']);
    Route::delete('pets/{id}', [PetsController::class, 'destroy']);
});

