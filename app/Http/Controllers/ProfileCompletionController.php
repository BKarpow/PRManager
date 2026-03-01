<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\UkrainePhone;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    public function edit() {
        return view('auth.complete-profile');
    }

    public function update(Request $request) {
        $request->validate([
            'phone' => ['required', 'string', 'unique:users,phone', new UkrainePhone],
        ]);

        $user = Auth::user();
        $user->phone = $request->phone; // Наш Mutator у моделі сам очистить номер
        $user->save();

        return redirect()->route('home')->with('status', 'Профіль оновлено!');
    }
}
