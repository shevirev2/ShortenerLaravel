<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-Api-Key');
        $validKey = env('API_KEY');

        if ($apiKey !== $validKey) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}
