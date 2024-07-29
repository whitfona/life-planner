<?php

use App\Http\Controllers\BoxController;
use Illuminate\Support\Facades\Route;
use App\Models\Box;

Route::controller(BoxController::class)->group(function () {
    Route::get('/boxes', 'index');
    Route::get('/boxes/{id}', 'show');
    Route::post('/boxes', 'store');
    Route::patch('/boxes/{id}', 'update');
    Route::delete('/boxes/{id}', 'destroy');
});