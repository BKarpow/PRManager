<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WightProductController;

Route::middleware(['auth'])->prefix('wproduct')->group(function () {
    Route::get('/', [WightProductController::class, 'index'])->name('wproduct.index');
    Route::get('/import', [WightProductController::class, 'import'])->middleware('admin')
    ->name('wproduct.import');
    Route::post('/import', [WightProductController::class, 'importStore'])->middleware('admin');
});
