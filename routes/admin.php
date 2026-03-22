<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/user/', [AdminUserController::class, 'index'])->name('admin.user.index');

    // Інші маршрути адмінки...
});
