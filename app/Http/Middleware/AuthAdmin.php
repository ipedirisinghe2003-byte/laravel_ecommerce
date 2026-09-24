<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $utype = strtolower((string) ($user->utype ?? ''));

        if (Auth::check() && in_array($utype, ['adm', 'sup'], true)) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'You are not authorized to access this page');
    }
}
