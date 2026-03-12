<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoBaseController;


// Publico
Route::post('login',[AuthController::class, 'login']);

//Autenticadas
Route::middleware('auth:api')->group(function () {

    Route::get('me', [AuthController::class, 'me']);

    // solo consulta: admin y usuario
    Route::get('productos', [ProductoBaseController::class, 'index'])
        ->middleware('role:admin,usuario');

    // crear producto: solo admin
    Route::post('productos', [ProductoBaseController::class, 'store'])
        ->middleware('role:admin');

    // consultar producto por id: admin y usuario
    Route::get('productos/{id}', [ProductoBaseController::class, 'show'])
        ->middleware('role:admin,usuario');

    // actualizar producto: solo admin
    Route::put('productos/{id}', [ProductoBaseController::class, 'update'])
        ->middleware('role:admin');

    // eliminar producto: solo admin
    Route::delete('productos/{id}', [ProductoBaseController::class, 'destroy'])
        ->middleware('role:admin');
});