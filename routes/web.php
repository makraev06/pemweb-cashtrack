<?php

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/', function () {
    return redirect('/dashboard');
});

use App\Http\Controllers\KasController;

Route::get('/kas', [KasController::class, 'index']);
Route::post('/kas/bayar', [KasController::class, 'bayar']);
Route::post('/kas/batal', [KasController::class, 'batal']);