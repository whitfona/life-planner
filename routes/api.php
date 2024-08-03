<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::controller(BoxController::class)->group(function () {
    Route::get('/boxes', 'index');
    Route::get('/boxes/{id}', 'show');
    Route::post('/boxes', 'store');
    Route::patch('/boxes/{id}', 'update');
    Route::delete('/boxes/{id}', 'destroy');
});

Route::controller(ItemController::class)->group(function () {
    Route::get('/items', 'index');
    Route::post('/items', 'store');
});