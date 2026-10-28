<?php

use App\Http\Controllers\ReciboFoliadoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/recibos-ingresos/{record}/imprimir', [ReciboFoliadoController::class, 'imprimir'])
    ->name('admin.recibos-ingresos.imprimir')
    ->middleware(['web', 'auth']);
