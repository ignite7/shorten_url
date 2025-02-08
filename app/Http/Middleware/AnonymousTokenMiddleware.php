<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\Urls\UpdateAnonymousToken;
use App\Enums\CookieKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class AnonymousTokenMiddleware
{
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

        $anonymousToken = $request->cookie(CookieKey::ANONYMOUS_TOKEN->value);

        UpdateAnonymousToken::runIf(! Str::isUuid($anonymousToken), Str::uuid()->toString());

        return $next($request);
    }
}
