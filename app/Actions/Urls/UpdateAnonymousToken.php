<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\CookieKey;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class UpdateAnonymousToken
{
    use AsController, AsObject;

    /**
     * @param string $anonymousToken
     * @return void
     */
    public function handle(string $anonymousToken): void
    {
        cookie()->queue(cookie(
            CookieKey::ANONYMOUS_TOKEN->value,
            $anonymousToken,
            262980 // 6 months
        ));
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'anonymous_token' => ['required', 'uuid'],
        ];
    }

    /**
     * @param ActionRequest $request
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->handle($request->string('anonymous_token')->value());

        return to_route('home');
    }
}
