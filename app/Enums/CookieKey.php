<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum CookieKey: string
{
    /**
     * @phpstan-use EnumUtils<string>
     */
    use EnumUtils;

    case ANON_TOKEN = 'anonToken';
}
