<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoApiController;

$prefixRoute = "/api";

Route::get($prefixRoute.'/shops', [InfoApiController::class, 'getShops'])
->name('api.info.shops');

// getGroupsFromShop

Route::get($prefixRoute.'/groups', [InfoApiController::class, 'getGroupsFromShop'])
->name('api.info.shops');
