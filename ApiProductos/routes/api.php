<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoBaseController;


// Publico
Route::post('login',[AuthController::class, 'login']);
Route::post('verify-code',[AuthController::class, 'verifyCode']);


//Autenticadas
Route::middleware('auth:api')->group(function () {

    Route::get('me', [AuthController::class, 'me']);
    
    // listar productos
    Route::get('productos', [ProductoBaseController::class, 'index'])
        ->middleware('role:admin,usuario,operador');

    // ver producto por id
    Route::get('productos/{id}', [ProductoBaseController::class, 'show'])
        ->middleware('role:admin,usuario,operador');

    // Logout y Refresh token
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh-token', [AuthController::class, 'refresh']);

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