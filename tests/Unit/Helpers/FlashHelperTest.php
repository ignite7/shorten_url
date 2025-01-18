<?php

declare(strict_types=1);

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use Illuminate\Support\Facades\Session;

it('can flash a message', function (): void {
    $message = 'This is a message';
    $type = fake()->randomElement(FlashMessageType::cases());

    FlashHelper::message($message, $type);

    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe($message)
        ->and(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe($type->value);
});
