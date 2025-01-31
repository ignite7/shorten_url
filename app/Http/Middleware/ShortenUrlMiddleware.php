<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Models\Request as RequestModel;
use Closure;
use Illuminate\Http\Request;
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

        if (! $anonToken = $request->cookie(CookieKey::ANON_TOKEN->value)) {
            FlashHelper::message('Unable to determine your anonymous token.', FlashMessageType::ERROR);

            return redirect()->back();
        }

        $requests = RequestModel::query()
            ->where('anon_token', $anonToken)
            ->whereDate('created_at', now())
            ->count();

        if ($requests >= 5) {
            FlashHelper::message('You have reached the maximum number of requests allowed per day.', FlashMessageType::ERROR);

            return redirect()->back();
        }

        return $next($request);
    }
}
