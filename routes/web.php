<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskViewController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [TaskViewController::class, 'index'])->name('tasks.index');

// Apenas as rotas do Breeze devem ter o middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware(['verified'])
        ->name('dashboard');
});

require __DIR__.'/auth.php';
