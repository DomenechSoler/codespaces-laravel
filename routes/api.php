<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TarjetasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use App\Http\Controllers\GameController;
use App\Models\Tarjetas;

// RUTAS PÚBLICAS
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('tarjetas', [TarjetasController::class, 'index']);
Route::get('tarjetas/{id}', [TarjetasController::class, 'show']);
Route::get('tarjetas/category/{categoryId}', [TarjetasController::class, 'getByCategory']);
Route::get('my-cards', [TarjetasController::class, 'myCards']);
Route::get('public-cards', [TarjetasController::class, 'publicCards']);

// RUTAS PROTEGIDAS (USUARIO AUTENTICADO)
Route::middleware([IsUserAuth::class])->group(function () {
    // Usuario
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'getUser']);
    Route::get('users/{id}', [AuthController::class, 'getUserById']);
    Route::put('users/{id}', [AuthController::class, 'updateUser']);

    // Tarjetas (crear solo si está autenticado)
    Route::post('tarjetas', [TarjetasController::class, 'store']);
    Route::put('tarjetas/{id}', [TarjetasController::class, 'update']);
    Route::delete('tarjetas/{id}', [TarjetasController::class, 'destroy']);

    // Partidas
    Route::get('games', [GameController::class, 'index']);
    Route::post('games', [GameController::class, 'store']);
    Route::put('games/{game}/finish', [GameController::class, 'update']);
    Route::delete('games/{game}', [GameController::class, 'destroy']);
    Route::get('ranking', [GameController::class, 'ranking']);

    // Categorías
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});

// RUTAS SOLO ADMIN
Route::middleware([IsAdmin::class])->group(function () {
    // Usuarios
    Route::get('users', [AuthController::class, 'getUsers']);
    Route::get('users/{id}', [AuthController::class, 'getUser']);
    Route::put('users/{id}', [AuthController::class, 'updateUser']);
    Route::delete('users/{id}', [AuthController::class, 'deleteUser']);

    // Juegos
    Route::get('games/user/{id}', [GameController::class, 'getGamesByUserId']);

    // CRUD de partidas (admin) usando endpoint exclusivo
    Route::get('games-admin', [GameController::class, 'adminIndex']);
    Route::get('games-admin/{game}', [GameController::class, 'adminShow']);
    Route::put('games-admin/{game}', [GameController::class, 'adminUpdate']);
    Route::delete('games-admin/{game}', [GameController::class, 'adminDestroy']);
    Route::post('games-admin', [GameController::class, 'adminStore']);
});
