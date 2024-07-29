<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoxController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Box;
use Inertia\Inertia;

// Route::controller(BoxController::class)->group(function () {
//     Route::get('/boxes', 'index');
//     Route::get('/boxes/{id}', 'show');
//     Route::post('/boxes', 'store');
//     Route::patch('/boxes/{id}', 'update');
//     Route::delete('/boxes/{id}', 'destroy');
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
