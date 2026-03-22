<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Перевіряємо, чи користувач автентифікований та чи є він адміном
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Якщо ні — повертаємо на головну або показуємо помилку 403
        return redirect('/')->with('error', 'У вас немає прав доступу до цього розділу.');
    }
}
