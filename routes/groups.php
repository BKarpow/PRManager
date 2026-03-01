<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupProductController;

$prefixRoute = "/group";

Route::get($prefixRoute.'/', [GroupProductController::class, 'index'])
->name('group.index');

Route::get($prefixRoute.'/create', [GroupProductController::class, 'create'])
->name('group.create');

Route::post($prefixRoute.'/create', [GroupProductController::class, 'store'])
->name('group.create');

Route::get($prefixRoute.'/edit/{groupProduct}', [GroupProductController::class, 'edit'])
->name('group.edit');

Route::post($prefixRoute.'/edit/{groupProduct}', [GroupProductController::class, 'update'])
->name('group.edit.update');

Route::get($prefixRoute.'/delete/{groupProduct}', [GroupProductController::class, 'destroy'])
->name('group.delete');

// getGroups

Route::get($prefixRoute.'/info', [GroupProductController::class, 'getGroups'])
->name('group.info');

$prefixRoute = null;

