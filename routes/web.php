<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CronController;
use App\Http\Middleware\EnsurePhoneIsSet;

use App\Http\Controllers\ProfileCompletionController;

Route::middleware(['auth'])->group(function () {
    Route::get('/complete', [ProfileCompletionController::class, 'edit'])
    ->withoutMiddleware([EnsurePhoneIsSet::class])
    ->name('profile.complete');
    Route::post('/complete-profile', [ProfileCompletionController::class, 'update'])
    ->withoutMiddleware([EnsurePhoneIsSet::class])
    ->name('profile.complete.update');
});

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test/get', function () {
//     dd(file_get_contents('http://195.201.133.94:8000/bestbefore/v1/barcode?barcode=4820250941894'));
// });

// Route::get('/run-migrations', function () {
//     try {
//         // Виконуємо команду 'migrate'
//         Artisan::call('migrate', ['--force' => true]);

//         // Отримуємо результат виконання команди
//         $output = Artisan::output();

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Міграції виконано успішно.',
//             'output' => $output
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Помилка під час виконання міграцій: ' . $e->getMessage()
//         ], 500);
//     }
// })->middleware('web');

Auth::routes();
Route::get('/', [App\Http\Controllers\DateProductController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\DateProductController::class, 'index'])->name('home');
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/test', function(){
    dd(Auth::user()->phone);
})->middleware(['auth']);

Route::get('/cron/not', [CronController::class, 'senderExps']);

require __DIR__.'/shop.php';
require __DIR__.'/groups.php';
require __DIR__.'/product.php';
require __DIR__.'/exp.php';
require __DIR__.'/config.php';
require __DIR__.'/import.php';
require __DIR__.'/telegram.php';
require __DIR__.'/api.php';
