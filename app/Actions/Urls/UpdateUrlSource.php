<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Models\Url;
use App\Rules\SourceRule;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class UpdateUrlSource
{
    use AsController, AsObject;

    /**
     * @param  Url  $url
     * @param  string  $source
     * @return void
     */
    public function handle(Url $url, string $source): void
    {
        $url->update(['source' => $source]);
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return ['can:update,url'];
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return SourceRule::rules();
    }

    /**
     * @param  ActionRequest  $request
     * @param  Url  $url
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request, Url $url): RedirectResponse
    {
        $this->handle($url, $request->string('source')->value());

        if ($url->wasChanged('source')) {
            FlashHelper::message('The original link has been updated.', FlashMessageType::SUCCESS);
        } else {
            FlashHelper::message('The original link could not be updated.', FlashMessageType::ERROR);
        }

        return redirect()->back();
    }
}
