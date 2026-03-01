<?php

use Illuminate\Support\Facades\Route;

Route::get('/shop/get', [App\Http\Controllers\ShopController::class, 'getShopsApi'])
->name('shop.get');

Route::get('/shop/', [App\Http\Controllers\ShopController::class, 'index'])
->name('shop.index');

Route::get('/shop/{shop}', [App\Http\Controllers\ShopController::class, 'show'])
->name('shop.show');

Route::get('/shop/add', [App\Http\Controllers\ShopController::class, 'create'])
->name('shop.create');

Route::post('/shop/add', [App\Http\Controllers\ShopController::class, 'store'])
->name('shop.create.store');

Route::get('/shop/{shop}/edit', [App\Http\Controllers\ShopController::class, 'edit'])
->name('shop.edit');

Route::post('/shop/{shop}/edit', [App\Http\Controllers\ShopController::class, 'update'])
->name('shop.edit.update');

Route::get('/shop/{shop}/delete', [App\Http\Controllers\ShopController::class, 'destroy'])
->name('shop.delete');
