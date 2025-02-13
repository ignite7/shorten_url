<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\CookieKey;
use App\Http\Middleware\RedirectToSourceMiddleware;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class RedirectToSource
{
    use AsController, AsObject;

    /**
     * @param  ActionRequest  $request
     * @param  Url  $url
     * @return RedirectResponse
     */
    public function handle(ActionRequest $request, Url $url): RedirectResponse
    {
        StoreRequest::run(
            $request,
            $url->id,
            $request->user()?->id,
            $request->cookie(CookieKey::ANONYMOUS_TOKEN->value)
        );

        return redirect()->away($url->source);
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return [RedirectToSourceMiddleware::class];
    }

    /**
     * @param  ActionRequest  $request
     * @param  Url  $url
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request, Url $url): RedirectResponse
    {
        return $this->handle($request, $url);
    }
}
