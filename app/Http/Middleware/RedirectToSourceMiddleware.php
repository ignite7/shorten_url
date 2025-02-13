<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UrlStatus;
use App\Models\Url;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectToSourceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->route('url');

        if (is_string($url)) {
            $url = Url::query()->findOrFail($url);
        }

        if (! $url instanceof Url) {
            // @codeCoverageIgnoreStart
            abort(404);
            // @codeCoverageIgnoreEnd
        }

        if ($url->status === UrlStatus::INACTIVE->value) {
            abort(404);
        }

        return $next($request);
    }
}
