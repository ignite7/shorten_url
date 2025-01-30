<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$ip = $request->ip()) {
            FlashHelper::message('Unable to determine your IP address.', FlashMessageType::ERROR);
            return redirect()->back();
        }

        // Limit the number of requests per IP address to 5 per day if the user is not authenticated.
        if (!$request->user()) {
            $requests = RequestModel::query()
                ->where('ip_address', $ip)
                ->whereDate('created_at', now())
                ->count();

            if ($requests >= 5) {
                FlashHelper::message('You have reached the maximum number of requests allowed per day.', FlashMessageType::ERROR);
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
