<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramHandlerController;

$prefixRoute = "/8570176831:AAFPx1F-2zhWhChbzPC12uZOJaNHcYmZ2wk";
$nameAlias = 'telegram.';

Route::post($prefixRoute, [TelegramHandlerController::class, 'index'])
->name('telegram.hook');

Route::get('/telegram/test', [TelegramHandlerController::class, 'test'])
->name('telegram.test');

$prefixRoute = null;
$nameAlias = null;

