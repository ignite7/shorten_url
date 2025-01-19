<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Models\Request;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

final class StoreRequest
{
    use AsObject;

    public function handle(ActionRequest $request, string $urlId, ?string $userId = null): Request
    {
        return Request::query()->create([
            'url_id' => $urlId,
            'user_id' => $userId,
            'method' => $request->method(),
            'uri' => $request->fullUrl(),
            'query' => collect($request->query->all()),
            'headers' => collect($request->headers->all()),
            'body' => collect($request->all()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
