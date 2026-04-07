<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::middleware(['auth'])->prefix('invertory')->group(function () {

    Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');

    Route::prefix('api')->group(function () {
        Route::get('/list', [InventoryController::class, 'getLastInventory'])->name('inventory.api.getLastInventory');
        Route::post('/store', [InventoryController::class, 'store'])->name('inventory.api.store');
        Route::post('/store-item', [InventoryController::class, 'storeInventoryItem'])
        ->name('inventory.api.store.item');
        Route::post('/items-list', [InventoryController::class, 'getInventoryItems'])
        ->name('inventory.api.items');
    });

});
