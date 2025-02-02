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
        if ($request->user()) {
            return $next($request);
        }

        $anonymousTokenKey = CookieKey::ANONYMOUS_TOKEN->value;
        $anonymousToken = $request->cookie(CookieKey::ANONYMOUS_TOKEN->value);

        if (! $anonymousToken || ! Str::isUuid($anonymousToken)) {
            cookie()->queue(cookie(
                $anonymousTokenKey,
                Str::uuid()->toString(),
                self::MINUTES
            ));
        }

        return $next($request);
    }
}
