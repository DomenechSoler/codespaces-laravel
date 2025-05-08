<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TarjetasController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;



//PUBLIC ROUTES

Route::get('tarjetas', [TarjetasController::class, 'index']);
Route::get('tarjetas/{id}', [TarjetasController::class, 'show']);
Route::post('tarjetas', [TarjetasController::class, 'store']);
Route::put('tarjetas/{id}', [TarjetasController::class, 'update']);
Route::delete('tarjetas/{id}', [TarjetasController::class, 'destroy']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('tarjetas', [TarjetasController::class, 'index']);
Route::get('/tarjetas/{id}', [TarjetasController::class, 'show']);

//PROTECTED ROUTES
Route::middleware([IsUserAuth::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'getUser']);
    Route::post('tarjetas', [TarjetasController::class, 'store']);
});

//ADMIN ROUTES
Route::middleware([IsAdmin::class])->group(function () {

    Route::get('users', [AuthController::class, 'getUsers']);
    Route::get('/users/{id}', [AuthController::class, 'getUser']);
    Route::put('/users/{id}', [AuthController::class, 'updateUser']);
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
    Route::post('tarjetas', [TarjetasController::class, 'store']);
    Route::put('/tarjetas/{id}', [TarjetasController::class, 'update']);
    Route::delete('/tarjetas/{id}', [TarjetasController::class, 'destroy']);
});
