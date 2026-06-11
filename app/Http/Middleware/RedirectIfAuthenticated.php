<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    // Redireciona usuários autenticados para o dashboard
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
