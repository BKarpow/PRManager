<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DateProduct;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.index', [
            'products' => Product::count(),
            'exps' => DateProduct::count(),
            'users' => User::count(),
            'lastDate' => DateProduct::orderBy('created_at', 'desc')->first(),
            'lastProduct' => Product::select('name', 'created_at', 'updated_at')->orderBy('created_at', 'desc')->first()
        ]);
    }

    public function runMigrate()
    {
        try {
         // Виконуємо команду 'migrate'
         Artisan::call('migrate', ['--force' => true]);

         // Отримуємо результат виконання команди
         $output = Artisan::output();

         return redirect()->back()->withStatus('Міграції виконано успішно.'."<br><pre>{$$output}</pre>");

     } catch (\Exception $e) {
        return redirect()->back()->withStatus('Помилка під час виконання міграцій: ' . $e->getMessage());

     }
    }
}
