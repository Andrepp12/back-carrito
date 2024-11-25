<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\VentaController;


Route::post('/register', [AuthController::class, 'register']);
Route::middleware(['api', 'web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('ventas', VentaController::class);
    Route::apiResource('detalle-ventas', DetalleVentaController::class);
    Route::get('/clientes/{clienteId}/ventas', [VentaController::class, 'ventasPorCliente']);
    Route::get('/ventas/{ventaId}/detalles', [DetalleVentaController::class, 'detallesPorVenta']);
});

//  Route::apiResource('categorias', CategoriaController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
