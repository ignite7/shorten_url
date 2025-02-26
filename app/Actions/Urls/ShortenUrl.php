<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\CookieKey;
use App\Enums\SessionKey;
use App\Enums\UrlStatus;
use App\Helpers\FlashHelper;
use App\Http\Middleware\ShortenUrlMiddleware;
use App\Models\Url;
use App\Validations\SourceValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;
use Throwable;

final class ShortenUrl
{
    use AsController, AsObject;

    /**
     * @param  ActionRequest  $request
     * @return Url
     *
     * @throws Throwable
     */
    public function handle(ActionRequest $request): Url
    {
        return DB::transaction(static function () use ($request): Url {
            $userId = $request->user()?->id;
            $anonymousToken = $userId ? null : $request->cookie(CookieKey::ANONYMOUS_TOKEN->value);

            $url = Url::query()->create([
                'user_id' => $userId,
                'anonymous_token' => is_array($anonymousToken) ? null : $anonymousToken,
                'source' => $request->string('source'),
                'status' => UrlStatus::ACTIVE->value,
            ]);

            StoreRequest::run($request, $url->id, $userId, $anonymousToken);

            return $url;
        });
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return ['can:create,'.Url::class, ShortenUrlMiddleware::class];
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return SourceValidation::rules();
    }

    /**
     * @return array<string, string>
     */
    public function getValidationAttributes(): array
    {
        return SourceValidation::validationAttributes();
    }

    /**
     * @param  ActionRequest  $request
     * @return RedirectResponse
     *
     * @throws Throwable
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $url = $this->handle($request);

        FlashHelper::message('URL created successfully!');

        return back()->with(SessionKey::LAST_SHORTENED_URL->value, $url->id);
    }
}
