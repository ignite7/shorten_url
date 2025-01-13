<?php

namespace App\Http\Middleware;

use App\Models\Request as RequestModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortenUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $ip = $request->ip()) {
            return response(status: Response::HTTP_BAD_REQUEST);
        }

        // Limit the number of requests per IP address to 5 per day if the user is not authenticated.
        if (! $request->user()) {
            $requests = RequestModel::query()
                ->where('ip_address', $ip)
                ->whereDate('created_at', now())
                ->count();

            if ($requests > 5) {
                return response(status: Response::HTTP_TOO_MANY_REQUESTS);
            }
        }

        return $next($request);
    }
}
