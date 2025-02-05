<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        // @codeCoverageIgnoreStart
        return parent::version($request);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var array<string, mixed> $parentShare */
        $parentShare = parent::share($request);

        return [
            ...$parentShare,
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get(FlashHelper::MESSAGE_KEY),
                'type' => fn () => $request->session()->get(
                    FlashHelper::MESSAGE_TYPE_KEY,
                    FlashMessageType::SUCCESS->value
                ),
            ],
        ];
    }
}
