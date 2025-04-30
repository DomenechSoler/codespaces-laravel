<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TarjetasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/tarjetas', [TarjetasController::class, 'index']);
Route::get('/tarjetas/{id}', [TarjetasController::class, 'show']);
Route::post('/tarjetas', [TarjetasController::class, 'store']);
Route::put('/tarjetas/{id}', [TarjetasController::class, 'update']);
Route::delete('/tarjetas/{id}', [TarjetasController::class, 'destroy']);
