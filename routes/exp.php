<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DateProductController;

$prefixRoute = "/date";
$nameAlias = 'date.';

Route::get($prefixRoute.'/', [DateProductController::class, 'index'])
->name('date.index');

Route::get($prefixRoute.'/show/{dateProduct}', [DateProductController::class, 'show'])
->name('date.show');

Route::get($prefixRoute.'/group/{group}', [DateProductController::class, 'indexGroup'])
->name('date.group');

Route::get($prefixRoute.'/expired', [DateProductController::class, 'expired'])
->name('date.expired');

Route::get($prefixRoute.'/expired/{group}', [DateProductController::class, 'expiredGroup'])
->name('date.expiredGroup');

Route::get($prefixRoute.'/create', [DateProductController::class, 'create'])
->name('date.create');

Route::post($prefixRoute.'/create', [DateProductController::class, 'store'])
->name('date.create');

// dateExists

Route::post($prefixRoute.'/info/exists', [DateProductController::class, 'dateExists'])
->name('date.info.exists');

$prefixRoute = null;
$nameAlias = null;

