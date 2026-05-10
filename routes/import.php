<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

$prefixRoute = "/import";
$nameAlias = 'import.';

Route::get($prefixRoute.'/csv', [ImportController::class, 'import'])
->name('import.csv');
Route::post($prefixRoute.'/csv', [ImportController::class, 'uploadExcel'])
->name('import.csv');

Route::post($prefixRoute.'/csv', [ImportController::class, 'uploadProductExcel'])
->name('import.product.csv');


$prefixRoute = null;
$nameAlias = null;

