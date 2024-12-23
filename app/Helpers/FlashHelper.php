<?php

namespace App\Helpers;

use App\Enums\FlashMessageType;
use Illuminate\Support\Facades\Session;

final class FlashHelper
{
    public const string MESSAGE_KEY = 'message';

    public const string MESSAGE_TYPE_KEY = 'message_type';

    public static function message(string $message, FlashMessageType $type): void
    {
        Session::flash(self::MESSAGE_KEY, $message);
        Session::flash(self::MESSAGE_TYPE_KEY, $type->value);
    }
}
