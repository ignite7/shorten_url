<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\CookieKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class AnonymousTokenMiddleware
{
    public const int MINUTES = 262980; // 6 months

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $anonTokenKey = CookieKey::ANON_TOKEN->value;
        $response = $next($request);

        if (! $request->user() && ! $request->cookie($anonTokenKey)) {
            /** @phpstan-ignore method.notFound */
            $response->withCookie(cookie(
                $anonTokenKey,
                Str::uuid()->toString(),
                self::MINUTES
            ));
        }

        return $response;
    }
}
