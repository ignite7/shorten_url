<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\FlashMessageType;
use Illuminate\Support\Facades\Session;

final readonly class FlashHelper
{
    public const string MESSAGE_KEY = 'message';

    public const string MESSAGE_TYPE_KEY = 'message_type';

    /**
     * @param  string  $message
     * @param  FlashMessageType  $type
     * @return void
     */
    public static function message(string $message, FlashMessageType $type = FlashMessageType::SUCCESS): void
    {
        Session::flash(self::MESSAGE_KEY, $message);
        Session::flash(self::MESSAGE_TYPE_KEY, $type->value);
    }
}
