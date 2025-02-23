<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\FlashMessageType;
use App\Enums\UrlStatus;
use App\Helpers\FlashHelper;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class ToggleUrlStatus
{
    use AsController, AsObject;

    /**
     * @param  Url  $url
     * @return void
     */
    public function handle(Url $url): void
    {
        $url->update([
            'status' => $url->status === UrlStatus::ACTIVE->value
                ? UrlStatus::INACTIVE->value
                : UrlStatus::ACTIVE->value,
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return ['can:update,url'];
    }

    /**
     * @param  Url  $url
     * @return RedirectResponse
     */
    public function asController(Url $url): RedirectResponse
    {
        $this->handle($url);

        if ($url->wasChanged('status')) {
            FlashHelper::message("The url status now is $url->status.");
        } else {
            FlashHelper::message('The url status could not be changed.', FlashMessageType::ERROR);
        }

        return back();
    }
}
