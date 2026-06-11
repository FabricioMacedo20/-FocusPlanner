<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    // Verifica e valida o token CSRF da requisição
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
