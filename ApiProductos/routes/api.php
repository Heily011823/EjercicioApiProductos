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
    
    // listar productos
    Route::get('productos', [ProductoBaseController::class, 'index'])
        ->middleware('role:admin,usuario,operador');

    // ver producto por id
    Route::get('productos/{id}', [ProductoBaseController::class, 'show'])
        ->middleware('role:admin,usuario,operador');

    // crear producto
    Route::post('productos', [ProductoBaseController::class, 'store'])
        ->middleware('role:admin,operador');

    // actualizar producto
    Route::put('productos/{id}', [ProductoBaseController::class, 'update'])
        ->middleware('role:admin,operador');

    // eliminar producto
    Route::delete('productos/{id}', [ProductoBaseController::class, 'destroy'])
        ->middleware('role:admin');
        
});