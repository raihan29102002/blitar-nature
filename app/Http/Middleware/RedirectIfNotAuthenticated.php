<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if (!in_array($request->url(), [route('login'), route('register')])) {
                session()->put('url.intended', $request->fullUrl());
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
