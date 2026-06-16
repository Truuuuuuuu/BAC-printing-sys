<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BidderController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DocEditorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->prefix('project')
    ->name('project.')
    ->group(function () {
    
    Route::get('/index', [ProjectController::class,'index'])
        ->middleware('permission:view-project')
        ->name('index');

    Route::post('/store', [ProjectController::class,'store'])
        ->middleware('permission:add-project')
        ->name('store');

    Route::put('/{project}/edit', [ProjectController::class,'update'])
        ->middleware('permission:edit-project')
        ->name('update');

    Route::delete('/{project}/delete', [ProjectController::class,'destroy'])
        ->middleware('permission:delete-project')
        ->name('destroy');

    Route::put('/{bid}/award', [ProjectController::class,'award'])
        ->middleware('permission:award-bid')
        ->name('award');

    Route::get('/{project}/detail', [ProjectController::class,'show'])
        ->middleware('permission:view-project')
        ->name('show');

});


Route::middleware(['auth', 'verified'])
    ->prefix('bidder')
    ->name('bidder.')
    ->group(function () {
    
    Route::get('/index', [BidderController::class,'index'])
        ->middleware('permission:view-bid')
        ->name('index');

    Route::post('/store', [BidderController::class, 'store'])
        ->middleware('permission:add-bid')
        ->name('store');

    Route::put('/{bid}/edit', [BidderController::class,'update'])
        ->middleware('permission:edit-bid')
        ->name('update');

    Route::delete('/{bid}/delete', [BidderController::class,'destroy'])
        ->middleware('permission:delete-bid')
        ->name('destroy');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//unsused atm, useful for pdf
Route::middleware('auth')
    ->prefix('pdf')
    ->name('pdf.')
    ->group(function(){
   
    Route::get('/projects', [PdfController::class, 'projects'])->name('projects');
    Route::get('/bids', [PdfController::class, 'bids'])->name('bids');
    Route::get('/bac-resolution-declarating-lcrb', [PdfController::class,'resolution'])->name('brdl');
});





// Route::get('/{project}/doc-editor', [DocEditorController::class, 'show'])->name('doc-editor.show');
// Route::post('/doc-editor/export', [DocEditorController::class, 'export'])->name('doc-editor.export');
// Route::post('/doc-preview', [DocEditorController::class, 'preview'])->name('doc-editor.preview');
// Route::get('/doc-template', function () {
//     return response()->file(public_path('docs/BAC Resolution Declaring LCRB.docx'), [
//         'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//     ]);
// })->name('doc-template');


Route::prefix('{project}')
    ->middleware(['permission:edit-docs','permission:print-docs'])
    ->name('doc.')
    ->group(function () {
    

    Route::get('doc-editor/{template}',         [DocEditorController::class, 'show'])
        ->name('editor-show');
 
    Route::post('doc-editor/{template}/export', [DocEditorController::class, 'export'])
        ->name('editor-export');
 
    Route::post('doc-editor/{template}/preview',[DocEditorController::class, 'preview'])
        ->name('editor-preview');
 
    Route::get('doc-editor/{template}/file',    [DocEditorController::class, 'file'])
        ->name('doc-template');
 
});

    Route::middleware(['auth','role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function(){
        
        Route::get('/index', [AdminController::class,'index'])->name('index');
        Route::get('/audit-logs',[AdminController::class,'auditLogs'])->name('audit-logs');
        Route::get('/all-users',[AdminController::class,'users'])->name('users');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])
            ->name('users.reset-password');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });



require __DIR__.'/auth.php';
