<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Http\Middleware\ShortenUrlMiddleware;
use App\Models\Url;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class ShortenUrl
{
    use AsController;

    public function handle(ActionRequest $request): Url
    {
        $userId = $request->user()?->id ?? null;

        $url = Url::query()->create([
            ...$request->validated(),
            'user_id' => $userId,
        ]);

        StoreRequest::run($request, $url->id, $userId);

        return $url;
    }

    public function getControllerMiddleware(): array
    {
        return ['guest', ShortenUrlMiddleware::class];
    }

    public function rules(): array
    {
        return [
            'source' => ['required', 'url', 'min:10', 'max:255'],
        ];
    }

    public function asController(ActionRequest $request): Application|Response|ResponseFactory
    {
        $this->handle($request);

        FlashHelper::message('URL created successfully!', FlashMessageType::SUCCESS);

        return response(status: ResponseAlias::HTTP_CREATED);
    }
}
