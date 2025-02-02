<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum SessionKey: string
{
    /**
     * @phpstan-use EnumUtils<string>
     */
    use EnumUtils;

    case LAST_SHORTENED_URL = 'last_shortened_url';
}
