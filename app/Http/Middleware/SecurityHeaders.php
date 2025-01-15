<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $nonce = Str::random(32); // Gera o nonce
        $response->headers->set('X-Nonce', $nonce); // Disponibiliza para o frontend
        $response->headers->set('Content-Security-Policy', $this->generateCspHeader($nonce));

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    protected function generateCspHeader($nonce): string
    {
        $policies = config('security.csp');

        // Adiciona o nonce à diretiva script-src
        $policies['script-src'] = array_merge($policies['script-src'], ["'nonce-" . $nonce . "'"]);

        return collect($policies)
            ->map(fn($sources, $directive) => $directive . ' ' . implode(' ', $sources))
            ->join('; ');
    }
}
