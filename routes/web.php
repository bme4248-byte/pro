<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PanelController;

Route::get('/', function() {
    return redirect()->route('panel');
});

// Ruta para el panel principal
Route::get('/panel', function() {
    return view('panel.index');
})->name('panel');

Route::get('/login', function() {
    return view('auth.login');
});

// Rutas para categorías (CRUD completo)
Route::resource('categorias', CategoriaController::class);

// Rutas para productos
Route::resource('productos', ProductoController::class);
// Rutas para detalle de pedidos
Route::resource('detalle-pedidos', DetallePedidoController::class);

// Rutas para pedidos
Route::resource('pedidos', PedidoController::class);

// Ruta adicional para cambiar estado del pedido
Route::post('pedidos/{id}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiar-estado');

// Rutas para pagos
Route::resource('pagos', PagoController::class);

// Ruta adicional para cambiar estado del pago
Route::post('pagos/{id}/cambiar-estado', [PagoController::class, 'cambiarEstado'])->name('pagos.cambiar-estado');