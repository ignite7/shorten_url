<?php

namespace App\Actions\Guest\Url;

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Models\Url;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Store
{
    use AsAction;

    public function handle(array $data): Url
    {
        return Url::query()->create($data);
    }

    public function getControllerMiddleware(): array
    {
        return ['guest'];
    }

    public function rules(): array
    {
        return [
            'source' => ['required', 'url', 'min:10', 'max:255'],
        ];
    }

    public function asController(ActionRequest $request): Application|Response|ResponseFactory
    {
        $this->handle($request->validated());
        FlashHelper::message('URL created successfully!', FlashMessageType::SUCCESS);

        return response(status: ResponseAlias::HTTP_CREATED);
    }
}
