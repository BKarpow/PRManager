<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsSet
{
    public function handle(Request $request, Closure $next)
    {
        // Якщо користувач увійшов, але не має телефону і не на сторінці оновлення
        if (Auth::check() && empty(Auth::user()->phone) && !$request->is('complete-profile*')) {
            return redirect()->route('profile.complete');
        }

        return $next($next);
    }
}
