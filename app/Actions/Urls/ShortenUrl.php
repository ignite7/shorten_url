<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Http\Middleware\ShortenUrlMiddleware;
use App\Models\Url;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class ShortenUrl
{
    use AsController, AsObject;

    public function handle(ActionRequest $request): Url
    {
        return DB::transaction(static function () use ($request): Url {
            $userId = $request->user()?->id;

            $url = Url::query()->create([
                'user_id' => $userId,
                'source' => $request->source,
            ]);

            StoreRequest::run($request, $url->id, $userId);

            return $url;
        });
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return [ShortenUrlMiddleware::class];
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'source' => ['required', 'url', 'min:10', 'max:255'],
        ];
    }

    public function asController(ActionRequest $request): Response|ResponseFactory
    {
        if ($request->user()?->cannot('create', Url::class)) {
            abort(ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $this->handle($request);

        FlashHelper::message('URL created successfully!', FlashMessageType::SUCCESS);

        return response(status: ResponseAlias::HTTP_CREATED);
    }
}
