<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BidderController;
use App\Http\Controllers\PdfController;
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
    Route::delete('/{project}/delete', [ProjectController::class,'destroy'])->name('destroy');

    Route::get('/{project}/detail', [ProjectController::class,'show'])->name('show');
});


Route::middleware(['auth', 'verified'])
    ->prefix('bidder')
    ->name('bidder.')
    ->group(function () {
    
    Route::get('/index', [BidderController::class,'index'])->name('index');
    Route::post('/store', [BidderController::class, 'store'])->name('store');
    Route::put('/{bid}/edit', [BidderController::class,'update'])->name('update');
    Route::delete('/{bid}/delete', [BidderController::class,'destroy'])->name('destroy');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->prefix('pdf')
    ->name('pdf.')
    ->group(function(){
   
    Route::get('/projects', [PdfController::class, 'projects'])->name('projects');
    Route::get('/bids', [PdfController::class, 'bids'])->name('bids');
    Route::get('/bac-resolution-declarating-lcrb', [PdfController::class,'resolution'])->name('brdl');
});



require __DIR__.'/auth.php';
