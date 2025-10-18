<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function() {
        return view('template');
});

Route::view('/panel', 'panel.index')->name('panel');

Route::get('/login', function() {
        return view('auth.login');
});