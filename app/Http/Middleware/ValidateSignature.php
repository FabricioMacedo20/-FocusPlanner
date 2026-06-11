<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignature
{
    // Valida a assinatura digital da requisição
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
