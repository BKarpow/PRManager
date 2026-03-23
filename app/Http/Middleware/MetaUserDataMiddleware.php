<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMetaData;

class MetaUserDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $r = UserMetaData::whereUserId(Auth::id())->first();
            if (!$r) {
                $r = new UserMetaData();
            }
            if ($r->block) {
                abort(403);
            }
            $r->user_id = Auth::id();
            $r->visit += 1;
            $r->last_ip = $request->ip();
            $r->last_user_agent = $request->userAgent();
            $r->save();
        }
        return $next($request);
    }
}
