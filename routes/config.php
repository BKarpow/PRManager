<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigController;

$prefixRoute = "/options";
$nameAlias = 'options.';

Route::get($prefixRoute.'/', [ConfigController::class, 'showConfigPage'])
->name('options.index');

Route::post($prefixRoute.'/', [ConfigController::class, 'saveConfig'])
->name('options.index');

Route::get($prefixRoute.'/info', [ConfigController::class, 'configInfo'])
->name('options.info');



$prefixRoute = null;
$nameAlias = null;

