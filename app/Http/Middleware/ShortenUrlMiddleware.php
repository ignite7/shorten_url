<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Enums\HttpMethod;
use App\Helpers\FlashHelper;
use App\Models\Request as RequestModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class ShortenUrlMiddleware
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

        if (! $ip = $request->ip()) {
            FlashHelper::message('Unable to determine your IP address.', FlashMessageType::ERROR);

            return back();
        }

        if (! Str::isUuid($request->cookie(CookieKey::ANONYMOUS_TOKEN->value))) {
            FlashHelper::message('Unable to determine your anonymous token.', FlashMessageType::ERROR);

            return back();
        }

        $requests = RequestModel::query()
            ->where('method', HttpMethod::POST->value)
            ->where('uri', route('urls.store'))
            ->where('ip_address', $ip)
            ->whereDate('created_at', now())
            ->count();

        if ($requests >= 5) {
            FlashHelper::message('You have reached the maximum number of requests allowed per day.', FlashMessageType::ERROR);

            return back();
        }

        return $next($request);
    }
}
