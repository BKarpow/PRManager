<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/run-migrate', [AdminController::class, 'runMigrate'])->name('admin.runMigrate');
    Route::get('/user/', [AdminUserController::class, 'index'])->name('admin.user.index');
    Route::post('/user/clear/cache', [AdminUserController::class, 'clearCache'])->name('admin.user.clearCache');

    // Інші маршрути адмінки...
});
