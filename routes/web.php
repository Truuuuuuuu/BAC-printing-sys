<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->prefix('project')
    ->name('project.')
    ->group(function () {
    
    Route::get('/index', [ProjectController::class,'index'])->name('index');
    Route::post('/store', [ProjectController::class,'store'])->name('store');
    Route::put('/{project}/edit', [ProjectController::class,'update'])->name('update');
});


Route::get('/bidder', function () {
    return view('bidder.index');
})->middleware(['auth', 'verified'])->name('bidder');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
