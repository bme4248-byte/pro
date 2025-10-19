<?php

use App\Http\Controllers\CategoriaController;
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